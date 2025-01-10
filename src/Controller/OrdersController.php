<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\OrdersDetails;
use App\Form\OrdersFormType;
use App\Repository\OrdersDetailsRepository;
use App\Repository\OrdersRepository;
use App\Repository\ProductRepository;
use App\Service\StripService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commande', name: 'app_orders_')]
final class OrdersController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(OrdersRepository $ordersRepository): Response
    {
        return $this->render('orders/index.html.twig', [
            'orders' => $ordersRepository->findBy([], ['id' => 'desc']),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET'])]
    public function newcommande(): Response
    {
        return $this->render('orders/new.html.twig');
    }

    #[Route('/ajout', name: 'add', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SessionInterface $sessionInterface,
        ProductRepository $productRepository
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', 'Accès refusé');
        $order = new Orders();
        $order->setUsers($this->getUser());
        $form = $this->createForm(OrdersFormType::class, $order);
        $form->handleRequest($request);
        $cart = $sessionInterface->get('cart', []);
        $data = [];
        $total = 0;
        foreach ($cart as $id => $quantity) {
            $product = $productRepository->find($id);
            $data[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }
        if ($form->isSubmitted() && $form->isValid()) {

            $cart = $sessionInterface->get('cart', []);
            if ($cart === []) {
                $this->addFlash('danger', 'Votre panier est vide');
                return $this->redirectToRoute('app_cart_call');
            }

            foreach ($cart as $item => $quantity) {
                $orderdetails = new OrdersDetails();

                $product = $productRepository->find($item);

                $price = $product->getPrice();

                $orderdetails->setPrice($price);
                $orderdetails->setQuantity($quantity);
                $orderdetails->setProduct($product);
                $order->addOrdersDetail($orderdetails);
            }

            $entityManager->persist($order);
            $entityManager->flush();

            $srtipe = new StripService();
            $srtipe->StartPayment($cart, $sessionInterface, $productRepository);
            $stripeurl = $srtipe->getStripeRedirectUrl();
            return $this->redirect($stripeurl);
        }




        return $this->render('orders/new.html.twig', compact('form', 'total'));
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(
        $id,
        OrdersDetailsRepository $ordersDetailsRepository,
        OrdersRepository $ordersRepository
    ): Response {
        $orders = new Orders();
        $order = $ordersDetailsRepository->findBy(['orders' => $id], ['id' => 'desc']);
        $orderid = $ordersRepository->find($id);
        $orders = $ordersRepository->findBy(['id' => $id]);
        return $this->render('orders/show.html.twig', compact('order', 'orderid', 'orders'));
    }

    #[Route('/{id}/by', name: 'by', methods: ['GET'])]
    public function by(
        $id,
        OrdersRepository $ordersRepository,
        EntityManagerInterface $em
    ): Response {
        $order = $ordersRepository->find($id);
        $order->setCompleted(true);
        $order->setDatedeliver(new \DateTimeImmutable());
        $em->flush();


        return $this->redirectToRoute('app_orders_index');
    }

    #[Route('/{id}/edit', name: 'app_orders_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Orders $order, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrdersFormType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_orders_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('orders/edit.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_orders_delete', methods: ['POST'])]
    public function delete(Request $request, Orders $order, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $order->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_orders_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/order/buy', name: 'stripe', methods: ['GET', 'POST'])]
    public function stripe()
    {


        $this->addFlash('success', 'commande payée avec succès');
        return $this->redirectToRoute('app_orders_add', [], Response::HTTP_SEE_OTHER);
    }
}
