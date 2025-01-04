<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\SubCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;



class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository, SubCategoryRepository $subCategoryRepository,
    CategoryRepository $categoryRepository, SessionInterface $sessionInterface
    ): Response
    {
        $products = $productRepository->findBy([], [], 3);
        $allproducts = $productRepository->findBy(['iscompleted' => null], ['id' => 'DESC']);
        $shops = $productRepository->getProductBySubcategory('femme', 4);
        $shopmans = $productRepository->getProductBySubcategory('homme', 4);
        $shopchilds = $productRepository->getProductBySubcategory('enfant', 4);
        $productwomans = $productRepository->getProductBySubcategory('femme', 8);
        $productmans = $productRepository->getProductBySubcategory('homme', 8);
        $productchilds = $productRepository->getProductBySubcategory('enfant', 8);
        $allcategories = $categoryRepository->findAll();
        $promotions = $productRepository->findBy(['iscompleted' => true], ['id' => 'DESC']);


        $cart = $sessionInterface->get('cart', []);
        $data = [];
        $total = 0;
        $quantities = 0;
        foreach ($cart as $id => $quantity){
         $product = $productRepository->find($id);
         $data[] = [
             'product' => $product,
             'quantity' => $quantity,
             
         ];
         $total += $product->getPrice() * $quantity;
        $quantities += $quantity;
        }

        return $this->render('home/index.html.twig', compact('products', 'productwomans', 'productmans',
         'productchilds', 'allproducts', 'shops',
        'shopmans', 'shopchilds', 'allcategories',
         'quantities', 'total', 'data', 'promotions'));
    }

    #[Route('/contact', name: 'app_home_contact')]
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig');
       
    }


    #[Route('/show/{id}', name: 'app_home_show')]
    public function show(Product $product, ProductRepository $productRepository): Response
    {
        $categories = $productRepository->findBy(['subcategory' => $product->getSubCategory()], ['id'=> 'DESC'], 20);
        return $this->render('home/show.html.twig', compact('product', 'categories'));
       
    }

}
