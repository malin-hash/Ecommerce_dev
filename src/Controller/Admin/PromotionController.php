<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/promotion', name: 'app_admin_promotion_')]
class PromotionController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProductRepository $productRepository): Response
    {

        $promotions = $productRepository->findAll();
        return $this->render('admin/promotion/index.html.twig', compact('promotions'));
    }

    #[Route('/{id}/promouvoir', name: 'add')]
    public function addpromotion($id, ProductRepository $productRepository, EntityManagerInterface $em): Response
    {
        $promotion = $productRepository->find($id);
        $promotion->setCompleted(true);
        $em->flush();

        $this->addFlash('info', 'Promotion ajoutée');
        return $this->redirectToRoute('app_admin_promotion_index');
    }

    #[Route('/{id}/promo', name: 'delete')]
    public function deletepromotion($id, ProductRepository $productRepository, EntityManagerInterface $em): Response
    {
        $promotion = $productRepository->find($id);
        if($promotion->iscompleted() != 0){
            $promotion->setCompleted(null);
            $em->flush();  
             $this->addFlash('info', 'Promotion Supprimée');
             return $this->redirectToRoute('app_admin_promotion_index');
        }else{
            $this->addFlash('info', 'Aucune promotion n\'est disponible');
            return $this->redirectToRoute('app_admin_promotion_index');

        }
       

    }
}
