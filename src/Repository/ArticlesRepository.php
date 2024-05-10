<?php

namespace App\Repository;

use App\Entity\Articles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * Class ArticlesRepository
 * @package App\Repository
 *
 * @method Articles|null find($id, $lockMode = null, $lockVersion = null)
 *      Trouve une entité par son identifiant
 *
 * @method Articles|null findOneBy(array $criteria, array $orderBy = null)
 *      Trouve une entité par les critères spécifiés
 *
 * @method Articles[]    findAll()
 *      Récupère toutes les entités
 *
 * @method Articles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *      Récupère toutes les entités correspondant aux critères spécifiés
 */
class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Articles::class);
    }

    /*public function PaginateArticle(int $page,int $limit):PaginationInterface
    {
        return $this->paginator->paginate(
            $this->createQueryBuilder('a')
                ->orderBy('a.id', 'DESC')
                ->setFirstResult($limit * ($page - 1))
                ->setMaxResults($limit)
                ->getQuery(),
            $page,
            $limit
        );

        /*return new PaginateArticle($this
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit)
            ->getQuery()
            setHint(Paginator::HINT_ENABLE_DISTINCT, false),
            false
    );
    }*/

    /**
     * Retourne un tableau d'objets Articles
     * en fonction d'un exemple de champ
     *
     * @param mixed $value
     * @return Articles[]
     */
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve une entité par un champ spécifique
     *
     * @param mixed $value
     * @return Articles|null
     */
    public function findOneBySomeField($value): ?Articles
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Trie les articles par le champ 'nom'
     *
     * @return Articles[]
     */
    public function orderByNom()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.nom', 'ASC')
            ->getQuery()->getResult();
    }

    /**
     * Trie les articles par le champ 'Categorie'
     *
     * @return Articles[]
     */
    public function orderByCategorie()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.Categorie', 'ASC')
            ->getQuery()->getResult();
    }

    public function searchByName(string $searchTerm): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.nom LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->orderBy('a.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByFilters( ?string $nom): array
    {
        $queryBuilder = $this->createQueryBuilder('o');


        if ($nom !== null) {
            $queryBuilder
                ->andWhere('o.nom LIKE :nom')
                ->setParameter('nom', '%'.$nom.'%');
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
