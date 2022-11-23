<?php

namespace App\Repository;

use App\Entity\CategoryMove;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryMove>
 *
 * @method CategoryMove|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryMove|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryMove[]    findAll()
 * @method CategoryMove[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryMoveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryMove::class);
    }

    public function add(CategoryMove $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CategoryMove $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return CategoryMove[] Returns an array of CategoryMove objects
    */
   public function findCatemoveByStyle($style): array
   {
       return $this->createQueryBuilder('c')
           ->andWhere('c.activated = 1')
           ->andWhere('c.style = :style')
           ->setParameter('style', $style)
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?CategoryMove
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
