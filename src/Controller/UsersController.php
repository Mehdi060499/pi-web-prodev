<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UserType;
use App\Form\UserFType;
use App\Form\DeleteType;
use App\Form\LoginFormType;
use App\Form\UserForgotPasswordType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\JsonResponse;
use Dompdf\Dompdf;
use Dompdf\Options;
use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpClient\HttpClient;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Vendeur;
use App\Form\VendeurFormType;
use App\Repository\VendeurRepository;
use App\Entity\Stock;
use App\Entity\Logins;
use App\Form\StockType;
use App\Form\LoginVType;
use App\Form\LLoginType;
use App\Form\SignuppType;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;




class UsersController extends AbstractController
{
  
    #[Route('/users', name: 'app_users')]
    public function index(Request $request,SessionInterface $session,UserRepository $userRepository): Response
    {$userId = $request->getSession()->get('user_id');
    
        // Fetch the user data using the user_id
        $user2 = $userRepository->find($userId);
        return $this->render('users/home.html.twig', [
           'user2'=>$user2,
        ]);
     
    }

    #[Route('/profile', name: 'profile')]
    public function profile(Request $request,SessionInterface $session,UserRepository $userRepository): Response
    {    // Get the user_id from the session
        $userId = $request->getSession()->get('user_id');
    
        // Fetch the user data using the user_id
        $user = $userRepository->find($userId);
        return $this->render('users/Profilefront.html.twig', [
            'user' => $user,]);
       
        
    }

 

    #[Route('/signup', name: 'signup')]
    public function signup(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new Users();
    
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {

             // Hacher le mot de passe
            $hashedPassword = $passwordEncoder->encodePassword($user, $user->getMotdepasse());
            $user->setMotdepasse($hashedPassword);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
    
            // Redirection après inscription
             return $this->redirectToRoute('allusers');
        }
    
        return $this->render('users/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/userfront', name: 'userfront')]
    public function signup2(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new Users();
        $user->setRole(1); // Définir le rôle par défaut à 1
        
        $form2 = $this->createForm(UserFType::class, $user);
        $form2->handleRequest($request);
       // $pwd=user->get
        if ($form2->isSubmitted() && $form2->isValid()) {
            // Hacher le mot de passe
            $hashedPassword = $passwordEncoder->encodePassword($user, $user->getMotdepasse());
            $user->setMotdepasse($hashedPassword);
             $nomUtilisateur = $user->getNom();
            $emailUtilisateur = $user->getEmail();
          //  $message = "Bonjour $nomUtilisateur, votre compte $emailUtilisateur a été créé avec succès .";
         //   $userRepository->sms('+21654026538', $message);
            // Ajouter l'utilisateur à la base de données
            $userRepository->add($user);
            
            $this->addFlash('success', 'User registered successfully!');
            return $this->redirectToRoute('userfront2');
        }
    
        return $this->render('users/userfront.html.twig', [
            'form2' => $form2->createView(),
        ]);
    }



   #[Route('/userfront2', name: 'userfront2')]
public function userfront2(Request $request, EntityManagerInterface $entityManager , AuthenticationUtils $authenticationUtils, SessionInterface $session, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, VendeurRepository $vendeurRepository): Response
{
    // Créer le formulaire de connexion
    $form = $this->createForm(LoginFormType::class);
    
    // Récupérer les erreurs d'authentification
    $error = $authenticationUtils->getLastAuthenticationError();
    
    // Gérer la soumission du formulaire
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $formData = $form->getData();
        $user = $userRepository->findOneBy(['email' => $formData['email']]);
        $vendeur = $vendeurRepository->findOneBy(['email' => $formData['email']]);
        $ipaddress = '';
        $login = new Logins();

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        if ($user && $passwordEncoder->isPasswordValid($user, $formData['motdepasse'])) {
            // Créer la session de l'utilisateur
            $session->set('user_id', $user->getIdclient());

            // Redirection en fonction du rôle de l'utilisateur
            if ($user->getRole() === 0) {
                return new RedirectResponse($this->generateUrl('app_users'));
            } elseif ($user->getRole() === 1) {
                return new RedirectResponse($this->generateUrl('profile'));
            } elseif ($user->getRole() === 2) {
                $this->sendNotification();
                $errorMessage = "L'utilisateur est bloqué.";
                
            }
            
            
        } elseif ($vendeur && $passwordEncoder->isPasswordValid($vendeur, $formData['motdepasse']))
        {
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
        } 
        else {
            $error = 'Mot de passe incorrect';
        }
    }
    
    // Rendre la vue du formulaire de connexion
    return $this->render('users/userfront2.html.twig', [
        'loginForm' => $form->createView(),
        'error' => $error,
       
    ]);
}
    
    
    

    #[Route('/allusers', name: 'allusers')]
    public function allusers(Request $request,SessionInterface $session,UserRepository $userRepository): Response
    { $userId = $request->getSession()->get('user_id');
    
        // Fetch the user data using the user_id
        $user2 = $userRepository->find($userId);
        $users = $userRepository->findAll();

        return $this->render('users/allusers.html.twig', [
            'users' => $users,
            'user2'=>$user2,
            
        ]);
    }


    #[Route('/updateadmin', name: 'updateadmin')]
    public function updateadmin(): Response
    {
        return $this->render('users/updateadmin.html.twig');
    }


    #[Route('/Delete', name: 'Delete', methods: ['GET', 'POST'])]
    public function Delete(Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createForm(DeleteType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userId = $form->get('id')->getData();
            $user = $userRepository->find($userId);

            if (!$user) {
                throw $this->createNotFoundException('User not found');
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            return $this->redirectToRoute('allusers');
        }

        return $this->render('users/Delete.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_users_delete', methods: ['POST'])]
    public function delete2($id, UserRepository $userRepository, Request $request): Response
    {
        $users = $userRepository->find($id);
       
    
        if ($users) {
           
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($users);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('allusers', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/{id}/edit', name: 'update', methods: ['GET', 'POST'])]
    public function edit($id, Request $request, UserRepository $userRepository, ManagerRegistry $manager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $userRepository->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $newPassword = $form->get('motdepasse')->getData();
        if ($newPassword !== null) {
            // Hacher le nouveau mot de passe
            $hashedPassword = $passwordEncoder->encodePassword($user, $newPassword);
            $user->setMotdepasse($hashedPassword);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si un nouveau mot de passe a été fourni
           
    
            $em = $manager->getManager();
            $em->persist($user);
            $em->flush();
    
            return $this->redirectToRoute('allusers', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('users/update.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editt', name: 'update2', methods: ['GET', 'POST'])]
    public function edit2($id, Request $request, UserRepository $userRepository, ManagerRegistry $manager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $userRepository->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $newPassword = $form->get('motdepasse')->getData();
        if ($newPassword !== null) {
            // Hacher le nouveau mot de passe
            $hashedPassword = $passwordEncoder->encodePassword($user, $newPassword);
            $user->setMotdepasse($hashedPassword);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si un nouveau mot de passe a été fourni
           
    
            $em = $manager->getManager();
            $em->persist($user);
            $em->flush();
    
            return $this->redirectToRoute('profile', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('users/UpdateFront.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    

    #[Route('/logout', name: 'logout',methods: ['GET', 'POST'])]
    public function logout(Request $request): Response
    {
        // Invalidate the session
        $request->getSession()->invalidate();
    
        // Redirect to the login page or any other page after logout
        return $this->redirectToRoute('userfront2');
    }

   


    
    public function displaySortedByIdASC(Request $request,SessionInterface $session,UserRepository $userRepository)
    {$userId = $request->getSession()->get('user_id');
        $user2 = $userRepository->find($userId);
        $users = $this->getDoctrine()->getRepository(Users::class)->findBy([], ['idclient' => 'ASC']);
        return $this->render('users/allusers.html.twig', [
            'users' => $users,
            'user2'=>$user2,
        ]);
    }
    
    public function displaySortedByNomASC(Request $request,SessionInterface $session,UserRepository $userRepository)
    {$userId = $request->getSession()->get('user_id');
        $user2 = $userRepository->find($userId);
        $users = $this->getDoctrine()->getRepository(Users::class)->findBy([], ['nom' => 'ASC']);
        return $this->render('users/allusers.html.twig', [
            'users' => $users,
            'user2'=>$user2,
        ]);
    }
    
    public function displaySortedByIdDESC(Request $request,SessionInterface $session,UserRepository $userRepository)
    {$userId = $request->getSession()->get('user_id');
        $user2 = $userRepository->find($userId);
        $users = $this->getDoctrine()->getRepository(Users::class)->findBy([], ['idclient' => 'DESC']);
        return $this->render('users/allusers.html.twig', [
            'users' => $users,
            'user2'=>$user2,
        ]);
    }
    
    public function displaySortedByNomDESC(Request $request,SessionInterface $session,UserRepository $userRepository)
    {$userId = $request->getSession()->get('user_id');
        $user2 = $userRepository->find($userId);
        $users = $this->getDoctrine()->getRepository(Users::class)->findBy([], ['nom' => 'DESC']);
        return $this->render('users/allusers.html.twig', [
            'users' => $users,
            'user2'=>$user2,
        ]);
    }

  

    #[Route('/generate-pdf', name: 'generate_pdf')]
    public function generatePdf(Request $request, UserRepository $userRepository): Response
    {
        // Récupérer tous les utilisateurs
        $users = $userRepository->findAll();
    
        // Créer une vue PDF en passant les utilisateurs
        $html = $this->renderView('users/pdf_template.html.twig', [
            'users' => $users,
        ]);
    
        // Configuration de Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
    
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
    
        // (Optionnel) Configurez la mise en page PDF ici
    
        $dompdf->render();
    
        // Sortie du PDF généré
        $dompdf->stream("all_users.pdf", [
            "Attachment" => true
        ]);
    
        return new Response();
    }

    #[Route('/forgot-password', name: 'forgot_password')]
    public function forgotPassword(Request $request, UserRepository $userRepository, MailerInterface $mailer): Response
    {
        // Récupérer l'email soumis dans le formulaire
        $email = $request->request->get('email');
    
        // Rechercher l'utilisateur par son email
        $user = $userRepository->findOneBy(['email' => $email]);
    
        if (!$user) {
            // Si l'utilisateur n'existe pas, afficher un message d'erreur
            $this->addFlash('error', 'User with this email does not exist.');
        } else {
            // Générer un nouveau mot de passe aléatoire
            $newPassword = bin2hex(random_bytes(8)); // Génère un mot de passe de 16 caractères hexadécimaux aléatoires
    
            // Hacher le nouveau mot de passe
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
            // Mettre à jour le mot de passe de l'utilisateur
            $user->setMotdepasse($hashedPassword);
    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
    
            // Envoyer l'email avec le nouveau mot de passe
            $email = (new Email())
                ->from('your_email@example.com')
                ->to($user->getEmail())
                ->subject('Password Reset')
                ->text("Your new password is: $newPassword");
    
            $mailer->send($email);
    
            // Afficher un message de succès
            $this->addFlash('success', 'Password reset email has been sent with your new password.');
        }
    
        return $this->redirectToRoute('forgot_password');
    }



    private function sendNotification(): void
    {
        $notifier = NotifierFactory::create();

        // Create a notification
        $notification = (new Notification())
            ->setTitle(' Login Attempt Failed')
            ->setBody('User is blocked !!!!');
        //->setIcon(DIR.'/assets/img/warning.png');

        // Send the notification
        $notifier->send($notification);
    }


    #[Route('/user/stats', name: 'app_user_stat')]
    public function stats(UserRepository $userRepository)
    {
        $stats = $userRepository->getStatsByStatut();

        return $this->render('users/stats.html.twig', [
            'stats' => $stats,
        ]);
    }



}

