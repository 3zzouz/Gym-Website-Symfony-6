<?php

namespace App\Repository;

use App\Entity\OffreClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OffreClient>
 *
 * @method OffreClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method OffreClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method OffreClient[]    findAll()
 * @method OffreClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OffreClient::class);
    }

    //    /**
    //     * @return OffreClient[] Returns an array of OffreClient objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?OffreClient
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
