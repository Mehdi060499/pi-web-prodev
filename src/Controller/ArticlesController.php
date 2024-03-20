<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Articles;
use Doctrine\ORM\EntityManagerInterface;

class ArticlesController extends AbstractController
{
    #[Route('/articles', name: 'app_articles')]


    public function index(): Response
    {
        $articles = $this->getAll();

        return $this->render('articles/index.html.twig', [
            'controller_name' => 'ArticlesController',
            'message' => 'Bonjour, notre projet demard maintenant',
            'articles' => $articles, // Passer les articles à la vue

        ]);
    }


    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function ajouter(Articles $article): void
    {
        $this->entityManager->persist($article);
        $this->entityManager->flush();
    }

    public function modifier(Articles $article): void
    {
        $this->entityManager->flush();
    }

    public function supprimer(int $idArticle): void
    {
        $article = $this->entityManager->getRepository(Articles::class)->find($idArticle);
        if ($article) {
            $this->entityManager->remove($article);
            $this->entityManager->flush();
        }
    }

    public function getAll(): array
    {
        return $this->entityManager->getRepository(Articles::class)->findAll();
    }

    public function getOneByID(int $idArticle): ?Articles
    {
        return $this->entityManager->getRepository(Articles::class)->find($idArticle);
    }

    public function getOneByNom(string $nom): ?Articles
    {
        return $this->entityManager->getRepository(Articles::class)->findOneBy(['nom' => $nom]);
    }
}
