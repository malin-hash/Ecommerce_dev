<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart', name: 'app_cart_')]
class CartController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(ProductRepository $productRepository,
    SessionInterface $sessionInterface
    ): Response
    {
       $cart = $sessionInterface->get('cart', []);

       $data = [];
       $total = 0;
       foreach ($cart as $id => $quantity){
        $product = $productRepository->find($id);
        $data[] = [
            'product' => $product,
            'quantity' => $quantity
        ];
        $total += $product->getPrice() * $quantity; 
       }
       $this->render('cart/index.html.twig', compact('data', 'total'));
       return $this->redirectToRoute('app_home');

    } 

    #[Route('/call', name: 'call')]
    public function call(ProductRepository $productRepository,
    SessionInterface $sessionInterface
    ): Response
    {
       $cart = $sessionInterface->get('cart', []);

       $data = [];
       $total = 0;
       foreach ($cart as $id => $quantity){
        $product = $productRepository->find($id);
        $data[] = [
            'product' => $product,
            'quantity' => $quantity
        ];
        $total += $product->getPrice() * $quantity; 
       }
       return $this->render('cart/index.html.twig', compact('data', 'total'));
    }

    #[Route('/cart/{id}', name: 'add')]
    public function add(Product $product,
    SessionInterface $sessionInterface
    ): Response
    {
       // On recupère l'id
       $id = $product->getId();
       // On recupère l'utilisateur
       $cart = $sessionInterface->get('cart', []);

       if(empty($cart[$id])){
           $cart[$id] = 1;
       }else{
           $cart[$id]++;
       }

       $sessionInterface->set('cart', $cart);

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Product $product,
    SessionInterface $sessionInterface
    ): Response
    {
       // On recupère l'id
       $id = $product->getId();
       // On recupère l'utilisateur
       $cart = $sessionInterface->get('cart', []);

       if(!empty($cart[$id])){
           unset($cart[$id]);
       }
       $sessionInterface->set('cart', $cart);

        return $this->redirectToRoute('app_cart_call');
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(Product $product,
    SessionInterface $sessionInterface
    ): Response
    {
       // On recupère l'id
       $id = $product->getId();
       // On recupère l'utilisateur
       $cart = $sessionInterface->get('cart', []);

       if(!empty($cart[$id])){
           if($cart[$id] > 1){
            $cart[$id]--;
           }else{
           unset($cart[$id]);
       }
       }

       $sessionInterface->set('cart', $cart);

        return $this->redirectToRoute('app_cart_call');
    }

    #[Route('/add/{id}', name: 'addplus')]
    public function addplus(Product $product,
    SessionInterface $sessionInterface
    ): Response
    {
       // On recupère l'id
       $id = $product->getId();
       // On recupère l'utilisateur
       $cart = $sessionInterface->get('cart', []);

       if(empty($cart[$id])){
           $cart[$id] = 1;
       }else{
           $cart[$id]++;
       }

       $sessionInterface->set('cart', $cart);

        return $this->redirectToRoute('app_cart_call');
    }

    #[Route('/supprimer/{id}', name: 'sup')]
    public function sup(Product $product,
    SessionInterface $sessionInterface
    ): Response
    {
       // On recupère l'id
       $id = $product->getId();
       // On recupère l'utilisateur
       $cart = $sessionInterface->get('cart', []);
        if(!empty($cart)){
            unset($cart[$id]);
        }
        return $this->redirectToRoute('app_cart_call');
    }

    #[Route('/del/{id}', name: 'del')]
    public function del(Product $product,
    SessionInterface $sessionInterface
    ): Response
    {
       // On recupère l'id
       $id = $product->getId();
       // On recupère l'utilisateur
       $cart = $sessionInterface->get('cart', []);

       if(!empty($cart[$id])){
           unset($cart[$id]);
       }
       $sessionInterface->set('cart', $cart);
        return $this->redirectToRoute('app_home');
    }

}
