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
            $message = "Bonjour $nomUtilisateur, votre compte $emailUtilisateur a été créé avec succès .";
           // $userRepository->sms('+21693323188', $message);
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
public function userfront2(Request $request, AuthenticationUtils $authenticationUtils, SessionInterface $session, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder): Response
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
                $errorMessage = "L'utilisateur est bloqué.";
                return $this->render('error.html.twig', ['message' => $errorMessage]);
            }
        } else {
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


    #[Route('/Forgotpassword', name: 'Forgotpassword', methods: ['GET', 'POST'])]
    public function forgotPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer): Response
    {
        $form = $this->createForm(UserForgotPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(UserRepository::class)->findOneBy(['email' => $email]);

            if (!$user) {
                $this->addFlash('error', 'Aucun utilisateur trouvé avec cet email.');
                return $this->redirectToRoute('Forgotpassword');
            }

            // Générer un nouveau mot de passe
            $newPassword = bin2hex(random_bytes(8));
            $hashedPassword = $passwordEncoder->encodePassword($user, $newPassword);
            $user->setPassword($hashedPassword);

            $entityManager->flush();

            // Envoyer un email à l'utilisateur avec le nouveau mot de passe
            $emailMessage = (new Email())
                ->from('mehdikallel9@gmail.com')
                ->to($user->getEmail())
                ->subject('Réinitialisation du mot de passe')
                ->text('Votre nouveau mot de passe est : ' . $newPassword);

            $mailer->send($emailMessage);

            $this->addFlash('success', 'Un email contenant le nouveau mot de passe a été envoyé à votre adresse email.');
            return $this->redirectToRoute('userfront2');
        }

        return $this->render('users/Forgotpassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    
    
    #[Route('/{id}', name: 'app_users_delete2', methods: ['POST'])]
    public function delete2($id, UserRepository $userRepository, Request $request): Response
    {
        $users = $userRepository->find($id);
    
        if ($this->isCsrfTokenValid('delete'.$users->getidclient(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($users);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('allusers', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/logout', name: 'logout',methods: ['GET', 'POST'])]
    public function logout(Request $request): Response
    {
        // Invalidate the session
        $request->getSession()->invalidate();
    
        // Redirect to the login page or any other page after logout
        return $this->redirectToRoute('userfront2');
    }


}
