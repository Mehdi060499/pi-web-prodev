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
use App\Form\LoginVType;
use App\Form\LLoginType;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;



class VendeurController extends AbstractController
{
   

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

    #[Route('/search', name: 'app_vendeur_search', methods: ['GET'])]
    public function search(VendeurRepository $vendeurRepository, Request $request): Response
    {
        $searchTerm = $request->query->get('search');
        $vendeurs = $searchTerm ? $vendeurRepository->findBySearchTerm($searchTerm) : $vendeurRepository->findAll();
        
        return $this->render('vendeur/vendeur_list.html.twig', [
            'vendeurs' => $vendeurs
        ]);
    }

    #[Route('/{id}/deletev', name: 'app_vendeur_delete', methods: ['POST'])]
    public function delete($id, VendeurRepository $vendeurRepository, StockRepository $StockRepository, Request $request): Response
    {
        $vendeur = $vendeurRepository->find($id);
        $stock = $StockRepository->findOneBy(['Idvendeur' => $id]);
    
        if ($vendeur) {
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

    #[Route('/{id}/edit2', name: 'app_update', methods: ['GET', 'POST'])]
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

    #[Route('/logout', name: 'logout2',methods: ['GET', 'POST'])]
public function logout(Request $request): Response
{
    // Invalidate the session
    $request->getSession()->invalidate();

    // Redirect to the login page or any other page after logout
    return $this->redirectToRoute('loginv');
}

#[Route('/signupp', name: 'signupp', methods: ['GET', 'POST'])]
    public function newVendeurr(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
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
    
        return $this->render('vendeur/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

  

 

}