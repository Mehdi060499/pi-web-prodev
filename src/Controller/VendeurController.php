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

class VendeurController extends AbstractController
{
    #[Route('/inscription', name: 'app_vendeur_inscription', methods: ['GET', 'POST'])]
    public function newVendeur(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vendeur = new Vendeur();
        $form = $this->createForm(VendeurFormType::class, $vendeur);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
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
    public function delete($id, VendeurRepository $vendeurRepository, Request $request): Response
    {
        $vendeur = $vendeurRepository->find($id);
    
        if ($this->isCsrfTokenValid('delete'.$vendeur->getIdvendeur(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($vendeur);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_vendeur', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_update', methods: ['GET', 'POST'])]
    public function edit($id,Request $request, VendeurRepository $vendeurRepository,ManagerRegistry $manager): Response
    {
        $vendeur = $vendeurRepository->find($id);
        $form = $this->createForm(VendeurFormType::class, $vendeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $manager->getManager();
            $em->persist($vendeur);
            $em->flush();

            return $this->redirectToRoute('app_vendeur', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vendeur/update.html.twig', [
            'vendeur' => $vendeur,
            'form' => $form,
        ]);
    }
}

