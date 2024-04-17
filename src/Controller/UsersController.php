<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UserType;
use App\Form\UserFType;
use App\Form\DeleteType;
use App\Form\LoginFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;






class UsersController extends AbstractController
{
  
    #[Route('/users', name: 'app_users')]
    public function index(): Response
    {
        return $this->render('users/home.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }

    #[Route('/profile', name: 'profile')]
    public function profile(): Response
    {
        return $this->render('users/Profilefront.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }

    #[Route('/signup', name: 'signup')]
    public function signup(Request $request): Response
    {
        $user = new Users();
    
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
    
            // Redirection après inscription
            // return $this->redirectToRoute('login');
        }
    
        return $this->render('users/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/userfront', name: 'userfront')]
    public function signup2(Request $request, UserRepository $userRepository): Response
    {
        $user = new Users();
        $user->setRole(1); // Définir le rôle par défaut à 1
        
        $form2 = $this->createForm(UserFType::class, $user);
        $form2->handleRequest($request);
    
        if ($form2->isSubmitted() && $form2->isValid()) {
            $userRepository->add($user);
            $this->addFlash('success', 'User registered successfully!');
            return $this->redirectToRoute('userfront2');
        }
    
        return $this->render('users/userfront.html.twig', [
            'form2' => $form2->createView(),
        ]);
    }


    #[Route('/userfront2', name: 'userfront2')]
    public function userfront2(Request $request, AuthenticationUtils $authenticationUtils, SessionInterface $session, UserRepository $userRepository): Response
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
              $pw = $user->getmotdepasse();
            // Vérifier les identifiants de connexion
            if ($user && $pw == $formData['motdepasse']) {
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
                $error = 'Invalid credentials';
            }
        }
        
        // Rendre la vue du formulaire de connexion
        return $this->render('users/userfront2.html.twig', [
            'loginForm' => $form->createView(),
            'error' => $error,
        ]);
        
        
    }
    
    
    

    #[Route('/allusers', name: 'allusers')]
    public function allusers(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('users/allusers.html.twig', [
            'users' => $users,
        ]);
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


}