<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Stripepayment;
use Doctrine\ORM\EntityManagerInterface;



//require __DIR__.'/../vendor/autoload.php';



class stripeController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

    }

    
    #[Route("/stripe/checkout", name:"stripe_checkout", methods:["GET","POST"])]

    public function checkout(Request $request): Response
    {
        Stripe::setApiKey('sk_test_51P9pU0IVDxqesvSmHKeF2KemORRiHnTuERv4r5xlX4nAPIUcgNnqfv0FFJcKtHfHzcmrVJdG8YZzI5Zy5iFVbudV006U5D22dB');
        Stripe::setApiVersion('2020-08-27');
        /*$cart = $request->getSession()->get('cart');
        $payment = new Stripepayment($this->entityManager);
        $session = $payment->startPayment($cart); // Appeler la méthode pour obtenir la session de paiement
*/
        // Récupérer le panier depuis la session
$cart = $request->getSession()->get('cart', []);

// Préparer les articles pour Stripe
$productsForStripe = [];
foreach ($cart as $article) {
    $productsForStripe[] = [
        'price_data' => [
            'currency' => 'eur',
            'unit_amount' => $article->getPrix() * 100, // Le montant doit être en cents
            'product_data' => [
                'name' => $article->getNom()
            ]
        ],
        'quantity' => 1
    ];
}
$successUrl = $this->generateUrl('articles_shop', [], UrlGeneratorInterface::ABSOLUTE_URL);
$cancelUrl = $this->generateUrl('panier', [], UrlGeneratorInterface::ABSOLUTE_URL);

     $session = Session::create([
         'payment_method_types'        => ['card'],
         'line_items' => $productsForStripe,
         'mode'                        => 'payment',
         'success_url'                 => $successUrl,
        'cancel_url'                  => $cancelUrl,
         'billing_address_collection'  => 'required',
         'shipping_address_collection' => [
             'allowed_countries' => ['FR']
         ]
     ]);

        //$payment->startpayment($article);
       // return new Response('Payment started successfully', Response::HTTP_OK);
        return $this->redirectToRoute('stripe_checkout', ['sessionId' => $session->id]);

        // Récupérer les données de paiement envoyées depuis la requête
      /*  $requestData = json_decode($request->getContent(), true);
        $subTotal = $requestData['subTotal'];
        $total = $requestData['total'];

        // Assurez-vous d'initialiser votre client Stripe avec vos clés API
        $stripe = new \Stripe\StripeClient($_ENV['STRIPE_SECRET_KEY']);

        // Créez la session de paiement Stripe
        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Votre commande',
                        ],
                        'unit_amount' => $total * 100, // Convertir le montant total en cents
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        // Redirigez l'utilisateur vers le processus de paiement de Stripe
        return $this->redirectToRoute('stripe_checkout', ['sessionId' => $session->id]);*/
    }
}
