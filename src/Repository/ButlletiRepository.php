<?php

namespace App\Repository;

use App\Entity\Butlleti;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Butlleti|null find($id, $lockMode = null, $lockVersion = null)
 * @method Butlleti|null findOneBy(array $criteria, array $orderBy = null)
 * @method Butlleti[]    findAll()
 * @method Butlleti[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ButlletiRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Butlleti::class);
    }

    // /**
    //  * @return Butlleti[] Returns an array of Butlleti objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Butlleti
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
