<?php

namespace App\Repository;

use App\Entity\Activite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Activite>
 */
class ActiviteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activite::class);
    }

    /**
     * @return Activite[] Returns an array of Activite objects
     */
    public function findTemps(): array
    {
        return $this->createQueryBuilder('a')
            ->select('a.heureDebut')
            ->orderBy('a.heureDebut', 'ASC')
            ->groupBy('a.heureDebut')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Activite[] Returns an array of Activite objects
     */
    public function findByTemps($val): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.heureDebut = :val')
            ->setParameter('val', $val)
            ->addOrderBy('a.jour', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return int Returns an array of Activite objects
     */
    public function findAllByTemps(): array
    {
        $results = $this->createQueryBuilder('a')
            ->select('a.heureDebut', 'a.jour', 'a.id')
            ->innerJoin('a.coach', 'c')
            ->addSelect('c.nom as coach')
            ->innerJoin('a.nom', 'n')
            ->addSelect('n.nom as nom')
            ->innerJoin('a.type', 't')
            ->addSelect('t.nom as type')
            ->orderBy('a.heureDebut', 'ASC')
            ->addOrderBy('a.jour', 'ASC')
            ->getQuery()
            ->getResult();
        $grouped = [];
        foreach ($results as $result) {
            $grouped[$result['heureDebut']][] = $result;
        }
        return $grouped;
    }



    //    public function findOneBySomeField($value): ?Activite
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
