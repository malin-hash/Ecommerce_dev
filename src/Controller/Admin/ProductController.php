<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\Stock;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/product', name: 'app_admin_product_')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('admin/product/index.html.twig', compact('products'));
    }

    #[Route('/add', name: 'addproduct')]
    public function add(Request $request, 
    EntityManagerInterface $em,
    ): Response
    {
        $product = new Product;
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            $product->setCreatedAt(new \DateTimeImmutable());
            $em->persist($product);
            $em->flush();
            
            $stock = new Stock();
            $stock->setQuantityproduct($product->getQuantity());
            $stock->setPriceproduct($product->getPrice());
            $stock->setNameproduct($product->getName());
            $stock->setDateaddproduct(new \DateTimeImmutable());

            $em->persist($stock);
            $em->flush();

            $this->addFlash('success', 'Le Produit est ajouté avec succès');
            return $this->redirectToRoute('app_admin_product_addproduct');
        }

        return $this->render('admin/product/addproduct.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/{id}', name: 'updateproduct')]
    public function update(Product $product, 
    Request $request, EntityManagerInterface $em): Response
    {
        
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', 'Le Produit est bien Modifié');
            return $this->redirectToRoute('app_admin_product_index');
        }
       
        return $this->render('admin/product/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/increase/{id}', name: 'increaseproduct')]
    public function increase(Product $product, 
    Request $request, EntityManagerInterface $em): Response
    {
        
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);
       
        return $this->render('admin/product/increaseproduct.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'deleteproduct')]
    public function delete(Product $product, EntityManagerInterface $em): Response
    {
            $em->remove($product);
            $em->flush();
            $this->addFlash('danger', 'Le Produit est bien Supprimé');
            return $this->redirectToRoute('app_admin_product_index');
    }
}
