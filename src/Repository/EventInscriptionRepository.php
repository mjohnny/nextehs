<?php

namespace App\Repository;

use App\Entity\EventInscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EventInscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventInscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventInscription[]    findAll()
 * @method EventInscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventInscriptionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EventInscription::class);
    }

//    /**
//     * @return EventInscription[] Returns an array of EventInscription objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventInscription
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
