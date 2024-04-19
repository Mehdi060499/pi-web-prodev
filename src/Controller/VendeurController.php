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
use App\Form\StockType;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class VendeurController extends AbstractController
{
    #[Route('/inscription', name: 'app_vendeur_inscription', methods: ['GET', 'POST'])]
    public function newVendeur(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $vendeur = new Vendeur();
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
    
            return $this->redirectToRoute('app_vendeur', ['vendeurId' => $vendeur->getIdvendeur()]);
        }
    
        return $this->render('vendeur/inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }

   #[Route('/news/{vendeurId}', name: 'app_stock_new', methods: ['GET', 'POST'])]
public function newStock(Request $request, VendeurRepository $vendeurRepository, $vendeurId , EntityManagerInterface $entityManager): Response
{
    $vendeur = $vendeurRepository->findOneBy(['idvendeur' => $vendeurId]);
    $stock = new Stock();
    $form = $this->createForm(StockType::class, $stock);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $stock->setIdvendeur($vendeur);
        $entityManager->persist($stock);
        $entityManager->flush();

        return $this->redirectToRoute('app_vendeur');
    }

    return $this->render('vendeur/news.html.twig', [
        'form2' => $form->createView(),
    ]);
}


    #[Route('/aff', name: 'app_vendeur', methods: ['GET'])]
    public function list(VendeurRepository $VendeurRepository , StockRepository $StockRepository): Response
    {
        return $this->render('vendeur/aff.html.twig', [
            'Vendeur' => $VendeurRepository->findAll(),
            'Stock' => $StockRepository->findAll()
        ]);
    }

    #[Route('/{id}', name: 'app_vendeur_delete', methods: ['POST'])]
    public function delete($id, VendeurRepository $vendeurRepository, StockRepository $StockRepository, Request $request): Response
    {
        $vendeur = $vendeurRepository->find($id);
        $stock = $StockRepository->findOneBy(['Idvendeur' => $id]);
    
        if ($this->isCsrfTokenValid('delete'.$vendeur->getIdvendeur(), $request->request->get('_token'))) {
            if($stock){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stock);
            $entityManager->remove($vendeur);
            $entityManager->flush();}
            else{$entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($vendeur);
                $entityManager->flush();}
        }
    
        return $this->redirectToRoute('app_vendeur', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_update', methods: ['GET', 'POST'])]
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

            return $this->redirectToRoute('app_vendeur', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vendeur/update.html.twig', [
            'vendeur' => $vendeur,
            'form' => $form,
        ]);
    }


    #[Route('/loginv', name: 'loginv')]
    public function userfront2(Request $request, AuthenticationUtils $authenticationUtils, SessionInterface $session, VendeurRepository $vendeurRepository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // Créer le formulaire de connexion
        $form = $this->createForm(LoginVType::class);
        
        // Récupérer les erreurs d'authentification
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // Gérer la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $veneur = $vendeurRepository->findOneBy(['email' => $formData['email']]);
            
            // Vérifier si l'utilisateur existe et si le mot de passe est correct
            if ($vendeur && $passwordEncoder->isPasswordValid($vendeur, $formData['motdepasse'])) {
                // Créer la session de l'utilisateur
                $session->set('vendeur_id', $vendeur->getIdvendeur());
    
                    return new RedirectResponse($this->generateUrl('app_vendeur'));

            } else {
                $error = 'Mot de passe incorrect';
            }
        }
           // Rendre la vue du formulaire de connexion
    return $this->render('vendeur/login.html.twig', [
        'loginForm' => $form->createView(),
        'error' => $error,
    ]);
}
}

