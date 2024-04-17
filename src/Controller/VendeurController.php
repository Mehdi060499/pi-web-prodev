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

class VendeurController extends AbstractController
{
    #[Route('/inscription', name: 'app_vendeur_inscription', methods: ['GET', 'POST'])]
    public function new(Request $request, VendeurRepository $VendeurRepository,ManagerRegistry $manager): Response
    {
        $em = $manager->getManager();
        $Vendeur = new Vendeur();
        $form = $this->createForm(VendeurFormType::class, $Vendeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           // $studentRepository->save($student, true);
           $em->persist($Vendeur);
           $em->flush();

            return $this->redirectToRoute('app_vendeur', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vendeur/inscription.html.twig', [
            'vendeur' => $Vendeur,
            'form' => $form,
        ]);
    }

    #[Route('/aff', name: 'app_vendeur', methods: ['GET'])]
    public function list(VendeurRepository $VendeurRepository): Response
    {
        return $this->render('vendeur/aff.html.twig', [
            'Vendeur' => $VendeurRepository->findAll(),
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

