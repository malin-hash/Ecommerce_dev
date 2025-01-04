<?php

namespace App\Controller;

use App\Repository\OrdersDetailsRepository;
use App\Repository\OrdersRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BillController extends AbstractController
{
    #[Route('/{{id}}/bill', name: 'app_bill')]
    public function index($id, OrdersRepository $ordersRepository, OrdersDetailsRepository $ordersDetailsRepository
    ): Response
    {
        $order = $ordersRepository->find($id);
        $orderdetails = $ordersDetailsRepository->findBy(['orders' => $id], ['id' => 'desc']);
        $date = new \DateTimeImmutable();
        $total = 0;
        foreach ($orderdetails as $item){
            $total += $item->getPrice()  * $item->getQuantity();
        }

        $option = new Options();
        $option->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($option);
        $html = $this->renderView('bill/index.html.twig', compact('order', 'orderdetails', 'date', 'total'));
        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream("malin_Shop".""." n° :".$order->getId().'.pdf', [
            'attachment' => false
        ]);

        return new Response('', 200, [
            'content-type' => 'application/pdf'
        ]);
    }

    #[Route('/{{id}}/view', name: 'app_view')]
    public function iview($id, OrdersRepository $ordersRepository, OrdersDetailsRepository $ordersDetailsRepository
    ): Response
    {
        $order = $ordersRepository->find($id);
        $orderdetails = $ordersDetailsRepository->findBy(['orders' => $id], ['id' => 'desc']);
        $date = new \DateTimeImmutable();
        $total = 0;
        foreach ($orderdetails as $item){
            $total += $item->getPrice()  * $item->getQuantity();
        }

        return $this->render('bill/view.html.twig', compact('order', 'orderdetails', 'date', 'total'));
    
    }
}
