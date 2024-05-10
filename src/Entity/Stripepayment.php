<?php

namespace App\Entity;

use Stripe\Checkout\Session;
use Stripe\Stripe;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Articles;
use App\Repository\ArticleRepository;






class Stripepayment
{
    private string $stripePublicKey;
    private EntityManagerInterface $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->stripePublicKey = $_ENV['stripePublicKey'];
    }

 public function startPayment(): Session
 {
     Stripe::setApiKey($this->stripePublicKey);
     Stripe::setApiVersion('2020-08-27');
     
     // Récupérer tous les articles depuis le repository
     /*$articleRepository = $this->entityManager->getRepository(Articles::class);
     $articles = $articleRepository->findAll();*/


 
// Récupérer le panier depuis la session
$cart = $request->getSession()->get('cart', []);

// Préparer les articles pour Stripe
$productsForStripe = [];
foreach ($cart as $item) {
    $productsForStripe[] = [
        'price_data' => [
            'currency' => 'eur',
            'unit_amount' => $item['price'] * 100, // Le montant doit être en cents
            'product_data' => [
                'name' => $item['name']
            ]
        ],
        'quantity' => 1
    ];
}
 
     $session = Session::create([
         'payment_method_types'        => ['card'],
         'line_items' => $productsForStripe,
         'mode'                        => 'payment',
         'success_url'                 => 'http://localhost:8000/success',
         'cancel_url'                  => 'http://localhost:8000/',
         'billing_address_collection'  => 'required',
         'shipping_address_collection' => [
             'allowed_countries' => ['FR']
         ]
     ]);
 
     return $session;
 }
}
