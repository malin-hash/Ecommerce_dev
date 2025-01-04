<?php

namespace App\Controller;

use App\Repository\OrdersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/order/delivered', name: 'app_order_delivered_')]
class OrderDeliveredOrNotController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {


        return $this->render('order_delivered_or_not/index.html.twig', [
            'controller_name' => 'OrderDeliveredOrNotController',
        ]);
    }

    #[Route('/livrer', name: 'delivered', methods: ['GET'])]
    public function delivered(OrdersRepository $ordersRepository): Response
    {
        $order = $ordersRepository->findBy(['Iscompleted' => true]);
        return $this->render('order_delivered_or_not/delivered.html.twig', compact('order'));
    }

    #[Route('/nonlivrer', name: 'nodelivered', methods: ['GET'])]
    public function nodelivered(OrdersRepository $ordersRepository): Response
    {
        $order = $ordersRepository->findBy(['Iscompleted' => null]);
        return $this->render('order_delivered_or_not/nodelivered.html.twig', compact('order'));
    }
}
