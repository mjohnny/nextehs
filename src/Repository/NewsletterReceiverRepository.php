<?php

namespace App\Repository;

use App\Entity\NewsletterReceiver;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NewsletterReceiver|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsletterReceiver|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsletterReceiver[]    findAll()
 * @method NewsletterReceiver[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsletterReceiverRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NewsletterReceiver::class);
    }

//    /**
//     * @return NewsletterReceiver[] Returns an array of NewsletterReceiver objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NewsletterReceiver
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
