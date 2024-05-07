<?php

namespace App\Controller;

use App\Entity\Vendeur;
use App\Form\VendeurFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VendeurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Stock;
use App\Entity\Logins;
use App\Form\StockType;
use App\Form\LoginVType;
use App\Form\LLoginType;
use App\Form\SignuppType;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpClient\HttpClient;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;



class Security extends AbstractController
{
    #[Route('/inscription2', name: 'app_vendeur_inscription1', methods: ['GET', 'POST'])]
    public function newVendeur(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder,FlashBagInterface $flashBag): Response
    {
        $vendeur = new Vendeur();
        $form = $this->createForm(SignuppType::class, $vendeur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recaptchaResponse = $request->request->get('g-recaptcha-response');

            if (empty($recaptchaResponse)) {
                $flashBag->add('error', 'Veuillez cocher le ReCaptcha.');
                return $this->redirectToRoute('app_vendeur_inscription1');
            }
            $hashedPassword = $passwordEncoder->encodePassword($vendeur, $vendeur->getMotdepasse());
            $vendeur->setMotdepasse($hashedPassword);
            $image = $form->get('image')->getData();
            if($image) // ajout image
            {
                $fileName = md5(uniqid()).'.'.$image->guessExtension();
                $image->move($this->getParameter('files_directory'), $fileName);
                $vendeur->setImage($fileName);
            } else {
               
                $vendeur->setImage("bb3faeefbe0d47b7d651c7e551fef7e0.png");
            }

            $entityManager->persist($vendeur);
            $entityManager->flush();
    
            return $this->redirectToRoute('userfront2');
        }
    
        return $this->render('vendeur/Signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/loginv', name: 'loginv')]
    public function loginvendeur(Request $request, EntityManagerInterface $entityManager , AuthenticationUtils $authenticationUtils, SessionInterface $session, VendeurRepository $vendeurRepository, UserPasswordEncoderInterface $passwordEncoder,FormFactoryInterface $formFactory): Response
    {
        // Créer le formulaire de connexion
        // Créer le formulaire de connexion
    $form = $formFactory->createNamedBuilder('loginForm', LLoginType::class)
    ->add('email')
    ->add('motdepasse')
    ->getForm();

// Récupérer les erreurs d'authentification
$error = $authenticationUtils->getLastAuthenticationError();

// Gérer la soumission du formulaire
$form->handleRequest($request);
if ($form->isSubmitted() && $form->isValid()) {
    $formData = $form->getData();
    $vendeur = $vendeurRepository->findOneBy(['email' => $formData['email']]);
    $ipaddress = '';
    $login = new Logins();
    

    // Vérifier si l'utilisateur existe et si le mot de passe est correct
    if ($vendeur && $passwordEncoder->isPasswordValid($vendeur, $formData['motdepasse'])) {
        // Créer la session de l'utilisateur
        $session->set('vendeur_id', $vendeur->getIdvendeur());
        $login->setIdvendeur($vendeur);
        $client = HttpClient::create();
    $response = $client->request('GET', 'http://ipinfo.io/json');
    $data = json_decode($response->getContent(), true);
    $ipaddress = $data['ip'];
       $login->setIp($ipaddress); 
       $entityManager->persist($login);
       $entityManager->flush();

        // Redirection en fonction du rôle de l'utilisateur
        // Modifier cette logique selon les besoins
        // Par exemple, pour un vendeur, vous pouvez rediriger vers une page spécifique
        return new RedirectResponse($this->generateUrl('app_profile'));
    } else {
        $error = 'Mot de passe incorrect';
    }
}


           // Rendre la vue du formulaire de connexion
    return $this->render('vendeur/Login.html.twig', [
        'loginForm' => $form->createView(),

        'error' => $error,
    ]);
}

#[Route('/profilev', name: 'app_profile')]
public function profile(Request $request,SessionInterface $session,VendeurRepository $vendeurRepository, StockRepository $StockRepository): Response
{    // Get the user_id from the session
    $vendeurid = $request->getSession()->get('vendeur_id');

    $client = new Client();

    $response = $client->get('https://api.ipdata.co?api-key=0b08ab039b22495c86a2be18bafa18896f52c25920bb53aa3267b834');
    
    $data = json_decode($response->getBody(), true);
    
    $ipAddress = $data['ip'];
    $latitude = $data['latitude'];
    $longitude = $data['longitude'];

    // Fetch the user data using the user_id
    $vendeur = $vendeurRepository->find($vendeurid);
    return $this->render('vendeur/Profilfront.html.twig', [
        'latitude' => $latitude,
        'longitude' => $longitude,
        'vendeur' => $vendeur,
        'stock' => $StockRepository->findAll()
    ]);
   
    
}

#[Route('/logout', name: 'logout',methods: ['GET', 'POST'])]
public function logout(Request $request): Response
{
    // Invalidate the session
    $request->getSession()->invalidate();

    // Redirect to the login page or any other page after logout
    return $this->redirectToRoute('loginv');
}

#[Route('/{id}/edit3', name: 'app_update3', methods: ['GET', 'POST'])]
public function edit($id,Request $request, VendeurRepository $vendeurRepository, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
{
    $vendeur = $vendeurRepository->find($id);
    $form = $this->createForm(VendeurFormType::class, $vendeur);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $hashedPassword = $passwordEncoder->encodePassword($vendeur, $vendeur->getMotdepasse());
        $vendeur->setMotdepasse($hashedPassword);
        $image = $form->get('image')->getData();
        if($image) // ajout image
        {
            $fileName = md5(uniqid()).'.'.$image->guessExtension();
            $image->move($this->getParameter('files_directory'), $fileName);
            $vendeur->setImage($fileName);
        } else {
           
            $vendeur->setImage("bb3faeefbe0d47b7d651c7e551fef7e0.png");
        }

        $entityManager->persist($vendeur);
        $entityManager->flush();

        return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('vendeur/updatefront.html.twig', [
        'vendeur' => $vendeur,
        'form' => $form,
    ]);
}


}