<?php

namespace App\Repository;

use App\Entity\Move;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Move>
 *
 * @method Move|null find($id, $lockMode = null, $lockVersion = null)
 * @method Move|null findOneBy(array $criteria, array $orderBy = null)
 * @method Move[]    findAll()
 * @method Move[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Move::class);
    }

    public function add(Move $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Move $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBySchool($user): ?array
    {
        return $this->createQueryBuilder('m')
             ->join('m.school', 's')
             ->join('s.members', 'me')
             ->andWhere('me.user = :user')
             ->setParameter('user', $user)
             ->andWhere('s.activated = 1')
             ->getQuery()
             ->getResult()
        ;
    }
}
