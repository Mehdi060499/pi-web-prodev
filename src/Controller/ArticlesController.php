<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Articles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ArticlesType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;





class ArticlesController extends AbstractController
{
    #[Route('/articles', name: 'app_articles')]


    public function index(): Response
    {
        $articles = $this->getAll();

        return $this->render('articles/index.html.twig', [
            'controller_name' => 'ArticlesController',
            'articles' => $articles, // Passer les articles à la vue

        ]);
    }


    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

   /* public function ajouter(Articles $article): void
    {
        $this->entityManager->persist($article);
        $this->entityManager->flush();
    }*/

      #[Route("/article/ajouter", name:"article_ajouter")]
    public function ajouter(Request $request): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'upload de l'image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('articles_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // Update the 'imagePath' property to store the image file name
                $article->setImage($newFilename);
            }

            // Persist and flush the article entity
            $entityManager =$this->entityManager;
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('articles');
        }

        return $this->render('articles/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function modifier(Articles $article): void
    {
        $this->entityManager->flush();
    }

   /* public function supprimer(int $idArticle): void
    {
        $article = $this->entityManager->getRepository(Articles::class)->find($idArticle);
        if ($article) {
            $this->entityManager->remove($article);
            $this->entityManager->flush();
        }
    }*/


    #[Route("/article/{id}", name:"article_supprimer", methods:"DELETE")]

    public function supprimer(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $article = $entityManager->getRepository(Articles::class)->find($id);

        if (!$article) {
            return new JsonResponse(['message' => 'Article not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $entityManager->remove($article);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Article deleted'], JsonResponse::HTTP_OK);
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
