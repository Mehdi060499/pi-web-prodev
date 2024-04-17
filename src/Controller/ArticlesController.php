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
//use Symfony\Component\Pagination\Paginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Tools\Pagination\Paginator;






class ArticlesController extends AbstractController
{
    #[Route('/articles', name: 'app_articles')]
    public function index(): Response
    {
        $articles = $this->getAll();

        return $this->render('articles/backoffice/index.html.twig', [
            'controller_name' => 'ArticlesController',
            'articles' => $articles, // Passer les articles à la vue

        ]);
    }

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route("/articles/ajouter", name:"article_ajouter")]
    public function ajouter(Request $request): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'upload de l'image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename ='/'.'Pictures/'.$originalFilename.'.'.$imageFile->guessExtension();
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

            return $this->redirectToRoute('app_articles');
        }

        return $this->render('articles/backoffice/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/articles/{id}/modifier", name:"article_modifier")]
    public function modifier(Request $request,int $id): Response
    {
     // Récupérer l'article à modifier
     $article = $this->entityManager->getRepository(Articles::class)->find($id);

     if (!$article) {
         throw $this->createNotFoundException('L\'article avec l\'ID ' . $id . ' n\'existe pas.');
     }

     // Créer le formulaire pré-rempli avec les données de l'article
     $form = $this->createForm(ArticlesType::class, $article);
     $form->handleRequest($request);
     if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'upload de l'image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename ='/'.'Pictures/'.$originalFilename.'.'.$imageFile->guessExtension();
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

        return $this->redirectToRoute('app_articles');
    }

     if ($form->isSubmitted() && $form->isValid()) {
         // Traiter les données du formulaire et mettre à jour l'article dans la base de données
         $this->entityManager->flush();

         // Rediriger vers une nouvelle page ou une autre action après la modification
         return $this->redirectToRoute('app_articles');
     }

     // Afficher le formulaire dans la vue de modification de l'article
     return $this->render('articles/backoffice/modifier.html.twig', [
         'form' => $form->createView(),
     ]);
    }


    #[Route("/articles/{id}/supprimer", name:"article_supprimer")]

    public function supprimer(int $id, EntityManagerInterface $entityManager):Response
    {
        $article = $entityManager->getRepository(Articles::class)->find($id);

        if (!$article) {
            return new JsonResponse(['message' => 'Article not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $entityManager->remove($article);
        $entityManager->flush();


        return $this->redirectToRoute('app_articles'); // Remplacez 'app_homepage' par le nom réel de votre route de page d'accueil

}


    public function getAll(): array
    {
        return $this->entityManager->getRepository(Articles::class)->findAll();
    }

    public function getOneByID(int $idArticle): ?Articles
    {
        return $this->entityManager->getRepository(Articles::class)->find($idArticle);
    }


    #[Route("/articles/show", name:"articles_show")]
    public function getOneByNom(string $nom): ?Articles
    {
        return $this->entityManager->getRepository(Articles::class)->findOneBy(['nom' => $nom]);
                // Vérifier si l'article est trouvé
        if ($article) {
            // Rendu d'un template avec l'article trouvé pour l'affichage dans une nouvelle fenêtre
            $template = $this->renderView('articles/backoffice/show.html.twig', [
                'article' => $article,
            ]);

            // Créer une nouvelle fenêtre avec le contenu du template rendu
            $response = new RedirectResponse("articles/backoffice
            /show.html.twig");  // Remplacer par votre route réelle pour une nouvelle fenêtre
            $response->setTarget('_blank');  // Définir la cible sur _blank pour ouvrir dans une nouvelle fenêtre
            $response->setContent($template);

            return $response;
        } else {
            // Gérer le cas où aucun article n'est trouvé (par exemple, afficher un message d'erreur)
            return new JsonResponse(['message' => 'Aucun article trouvé avec ce nom'], JsonResponse::HTTP_NOT_FOUND);
        }

    }

    #[Route("/articles/shop", name:"articles_shop")]
    public function paginateArticles(Request $request): Response
{
    // Créer une requête pour récupérer tous les articles
    $queryBuilder = $this->entityManager->getRepository(Articles::class)->createQueryBuilder('a');

    // Créer un objet Paginator avec la requête
    $paginator = new Paginator($queryBuilder);

    // Définir le nombre d'articles par page
    $limit = 8;

    // Obtenir la page courante (par défaut 1)
    $page = $request->query->getInt('page', 1);

    // Calculer l'offset
    $offset = ($page - 1) * $limit;

    // Définir les limites de la requête
    $paginator->getQuery()
        ->setFirstResult($offset)
        ->setMaxResults($limit);

    // Récupérer les articles paginés
    $articleP = $paginator->getIterator();

    // Afficher les articles et les liens de pagination
    return $this->render('articles/frontoffice/shop.html.twig', [
        'articleP' => $articleP,
        'currentPage' => $page,
        'totalPages' => ceil(count($paginator) / $limit),
    ]);
}

#[Route("/articles/{id}/delete", name:"supprimer_article_panier", methods: ["DELETE"]  )]
    public function deleteFromCart(Request $request, int $id): JsonResponse
    {
        // Récupérez le tableau du panier depuis la session
        $cart = $request->getSession()->get('cart', []);

        // Recherchez l'indice de l'article à supprimer dans le tableau du panier
        $index = array_search($id, $cart);

        // Si l'article est trouvé dans le panier, supprimez-le
        if ($index !== false) {
            unset($cart[$index]);
            $request->getSession()->set('cart', $cart);
            // Réponse JSON pour indiquer que la suppression a réussi
            return new JsonResponse(['success' => true]);
        } else {
            // Réponse JSON pour indiquer que l'article n'a pas été trouvé dans le panier
            return new JsonResponse(['success' => false, 'message' => 'L\'article n\'a pas été trouvé dans le panier'], 404);
        }
    }

#[Route("/articles/{id}/cart", name:"ajouter_panier")]
public function addToCart(Request $request, int $id): Response
{
    // Récupérez l'article à partir de l'ID (vous pouvez utiliser cette méthode ou toute autre méthode que vous utilisez)
    $article = $this->entityManager->getRepository(Articles::class)->find($id);

    if (!$article) {
        // Gérer le cas où l'article n'est pas trouvé
        throw $this->createNotFoundException('L\'article avec l\'ID ' . $id . ' n\'existe pas.');
    }

    // Ajoutez ici la logique pour ajouter l'article au panier.
    // Vous pouvez utiliser les sessions ou toute autre méthode pour stocker les articles dans le panier.

    // Par exemple, vous pouvez ajouter l'article à la session comme ceci :
    $cart = $request->getSession()->get('cart', []);
    foreach ($cart as $cartItem) {
        if ($cartItem->getId() === $article->getId()) {
            // Si l'article est déjà dans le panier, redirigez l'utilisateur ou affichez un message
            return $this->redirectToRoute('ajouter_panier', ['id' => $article->getId(),]); // Par exemple, redirigez vers la page d'accueil
        }}
    $cart[] = $article;
    $request->getSession()->set('cart', $cart);



    // Redirigez l'utilisateur vers une autre page ou affichez un message de succès.
    //return $this->redirectToRoute('app_articles'); // Redirigez vers la page d'accueil par exemple
    return $this->render('articles/frontoffice/cart.html.twig' , [
        'id' => $article->getId(),
        'cart' => $cart,]);

}

#[Route("/articles/cart", name:"panier")]
public function cart(Request $request): Response
{
    // Récupérez le panier depuis la session
    $cart = $request->getSession()->get('cart', []);

    // Affichez la page du panier en passant le contenu du panier à votre vue
    return $this->render('articles/frontoffice/cart.html.twig', [
        'cart' => $cart,
    ]);
}

    #[Route("/articles/{id}/Wishlist", name:"ajouter_wishlist")]
    public function addToWishlist(Request $request, int $id): Response
    {
        // Récupérez l'article à partir de l'ID (vous pouvez utiliser cette méthode ou toute autre méthode que vous utilisez)
        $article = $this->entityManager->getRepository(Articles::class)->find($id);

        if (!$article) {
            // Gérer le cas où l'article n'est pas trouvé
            throw $this->createNotFoundException('L\'article avec l\'ID ' . $id . ' n\'existe pas.');
        }

        // Ajoutez ici la logique pour ajouter l'article au panier.
        // Vous pouvez utiliser les sessions ou toute autre méthode pour stocker les articles dans le panier.

        // Par exemple, vous pouvez ajouter l'article à la session comme ceci :
        $wishlist = $request->getSession()->get('wishlist', []);
        foreach ($wishlist as $wishlistItem) {
            if ($wishlistItem->getId() === $article->getId()) {
                // Si l'article est déjà dans le panier, redirigez l'utilisateur ou affichez un message
                return $this->redirectToRoute('ajouter_wishlist', ['id' => $article->getId(),]); // Par exemple, redirigez vers la page d'accueil
            }}
        $wishlist[] = $article;
        $request->getSession()->set('wishlist', $wishlist);



        // Redirigez l'utilisateur vers une autre page ou affichez un message de succès.
        //return $this->redirectToRoute('app_articles'); // Redirigez vers la page d'accueil par exemple
        return $this->render('articles/frontoffice/wishlist.html.twig' , [
            'id' => $article->getId(),
            'wishlist' => $wishlist,]);

    }

    #[Route("/articles/Wishlist", name:"wishlist")]
public function wishlist(Request $request): Response
{
    // Récupérez le panier depuis la session
    $wishlist = $request->getSession()->get('wishlist', []);

    // Affichez la page du panier en passant le contenu du panier à votre vue
    return $this->render('articles/frontoffice/wishlist.html.twig', [
        'wishlist' => $wishlist,
    ]);
}
}


