<?php

namespace App\Repository;

use App\Entity\Ordre;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Ordre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ordre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ordre[]    findAll()
 * @method Ordre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ordre::class);
    }

    public function findOrdresByUser (User $user){
        return $this->createQueryBuilder('ordre')
            ->andWhere('ordre.estat = :V 
                OR ordre.estat = :blank')
            ->andWhere('users.id = :user')
            ->leftJoin('ordre.users', 'users')
            ->setParameter('V', 'V')
            ->setParameter('blank', '')
            ->setParameter('user', $user->getId());
    }

    // /**
    //  * @return Ordres[] Returns an array of Ordres objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ordres
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
