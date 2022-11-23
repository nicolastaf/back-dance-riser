<?php

namespace App\Repository;

use App\Entity\Choreography;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Choreography>
 *
 * @method Choreography|null find($id, $lockMode = null, $lockVersion = null)
 * @method Choreography|null findOneBy(array $criteria, array $orderBy = null)
 * @method Choreography[]    findAll()
 * @method Choreography[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChoreographyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Choreography::class);
    }

    public function add(Choreography $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Choreography $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Choreography[] Returns an array of Choreography objects
    */
   public function findByStyleBySchool($style, $schools): array
   {
       return $this->createQueryBuilder('c')
            ->join('c.school', 's')
            ->andWhere('c.style = :style')
            ->setParameter('style', $style)
            ->andWhere('c.school IN(:schools)')
            ->setParameter('schools', $schools)
            ->andWhere('s.activated = 1')
            ->getQuery()
            ->getResult()
       ;
   }

   public function findBySchool($user): ?array
   {
    return $this->createQueryBuilder('c')
         ->join('c.school', 's')
         ->join('s.members', 'm')
         ->andWhere('m.user = :user')
         ->setParameter('user', $user)
         ->andWhere('s.activated = 1')
         ->getQuery()
         ->getResult()
    ;
    }
}
