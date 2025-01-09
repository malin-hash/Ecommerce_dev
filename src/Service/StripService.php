<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class StripService
{
    private $redirectUrl;

    public function __construct()
    {
        Stripe::setApiKey($_SERVER['STRIPE_SECRET']);
        Stripe::setApiVersion('2024-12-18.acacia');
    }


    public function StartPayment($cart, $sessionInterface, $productRepository)
    {
        // $itemsCart = $cart;
        // $products = [];

        // foreach ($itemsCart as $value) {
        //     $productItem = [];
        //     $productItem['name'] = $value['product']->getName();
        //     $productItem['price'] = $value['product']->getPrice();
        //     $productItem['quantity'] = $value['quantity'];
        //     $products[] = $productItem;
        // }
        $cart = $sessionInterface->get('cart', []);

        $data = [];
        $total = 0;
        foreach ($cart as $id => $quantity) {
            $product = $productRepository->find($id);
            $data[] = [
                'product' => $product,
                'name' => $product->getName(),
                'quantity' => $quantity,
                'price' => $product->getPrice()
            ];
            $total += $product->getPrice() * $quantity;
        }
        $session = Session::create([
            'line_items' => [
                array_map(fn(array $datas) => [
                    'quantity' => $datas['quantity'],
                    'price_data' => [
                        'currency' => 'EUR',
                        'product_data' => [
                            'name' => $datas['name']
                        ],
                        'unit_amount' => $datas['price'] * 655
                    ]
                ], $data)
            ],
            'mode' => 'payment',
            'cancel_url' => 'http://127.0.0.1:8000/order/cancel',
            'success_url' => 'http://127.0.0.1:8000/commande/order/buy',
            'billing_address_collection' => 'required',
            'metadata' => []
        ]);
        $this->redirectUrl = $session->url;
    }

    public function getStripeRedirectUrl()
    {
        return $this->redirectUrl;
    }
}
