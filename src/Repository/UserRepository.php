<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Twilio\Rest\Client;

/**
 * @extends ServiceEntityRepository<users>
 *
 * @method users|null find($id, $lockMode = null, $lockVersion = null)
 * @method users|null findOneBy(array $criteria, array $orderBy = null)
 * @method users[]    findAll()
 * @method users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
        $this->entityManager = $registry->getManager();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Users $entity, bool $flush = true): void
    {
        $this->entityManager->persist($entity);
        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Users $entity, bool $flush = true): void
    {
        $this->entityManager->remove($entity);
        if ($flush) {
            $this->entityManager->flush();
        }
    }

     /**
     * @return Users[] Returns an array of Users objects
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('u')
            ->getQuery()
            ->getResult();
    }

    public function getStatsByStatut()
    {
        $qb = $this->createQueryBuilder('u')
            ->select('CASE WHEN u.role = 0 THEN \'Admin\' WHEN u.role = 1 THEN \'Utilisateur\' ELSE \'Utilisateur désactivé\' END as status, COUNT(u) as count')
            ->groupBy('u.role');
    
        return $qb->getQuery()->getResult();
    }
    

   /* public function sms(String $num, string $message): void
    {
        
        // Your Account SID and Auth Token from twilio.com/console
        $sid = 'AC370acbd74186d12f7f758c0f677df3e8';
        $auth_token = '570c37fa9f842d11bdd3c18b981930ac';
        // In production, these should be environment variables. E.g.:
         $auth_token = $_ENV["TWILIO_AUTH_TOKEN"];
        // A Twilio number you own with SMS capabilities
        $twilio_number = "+12564084038";

        $client = new Client($sid, $auth_token);
        $client->messages->create(
            // the number you'd like to send the message to
            $num,
            [
                // A Twilio phone number you purchased at twilio.com/console
                'from' => $twilio_number,
                // the body of the text message you'd like to send
                'body' =>$message,
            ]
        );
    }*/

    
    

}
