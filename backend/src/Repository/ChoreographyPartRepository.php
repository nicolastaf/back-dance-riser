<?php

namespace App\Repository;

use App\Entity\ChoreographyPart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChoreographyPart>
 *
 * @method ChoreographyPart|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChoreographyPart|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChoreographyPart[]    findAll()
 * @method ChoreographyPart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChoreographyPartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChoreographyPart::class);
    }

    public function add(ChoreographyPart $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ChoreographyPart $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ChoreographyPart[] Returns an array of ChoreographyPart objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ChoreographyPart
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
