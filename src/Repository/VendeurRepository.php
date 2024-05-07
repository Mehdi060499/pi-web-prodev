<?php

namespace App\Repository;

use App\Entity\Vendeur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticklockException;
use Doctrine\ORM\ORMException;

/**
 * @extends ServiceEntityRepository<Vendeur>
 *
 * @method Vendeur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vendeur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vendeur[]    findAll()
 * @method Vendeur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class VendeurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vendeur::class);
    }
    public function findBySearchTerm(string $searchTerm): array
{
    $query = $this->createQueryBuilder('v')
        ->where('v.nom LIKE :searchTerm OR v.nomproduit LIKE :searchTerm OR v.email LIKE :searchTerm OR v.description LIKE :searchTerm')
        ->setParameter('searchTerm', '%'. $searchTerm. '%')
        ->getQuery()
        ->getResult();

    return $query;
}
    //    /**
//     * @return Vendeur[] Returns an array of vendeur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Student
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
