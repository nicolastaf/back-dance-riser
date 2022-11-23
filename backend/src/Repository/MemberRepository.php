<?php

namespace App\Repository;

use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Member>
 *
 * @method Member|null find($id, $lockMode = null, $lockVersion = null)
 * @method Member|null findOneBy(array $criteria, array $orderBy = null)
 * @method Member[]    findAll()
 * @method Member[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Member::class);
    }

    public function add(Member $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Member $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Member[] Returns an array of Member objects
    */
   public function findByMemberBySchool($school, array $roles): ?array
   {
        $qb = $this->createQueryBuilder('m');
        $qb
            ->join('m.user', 'user'); 
        foreach ($roles as $role) {
            $qb
                ->orWhere("user.roles LIKE :$role")
                ->setParameter($role, '%"' . $role . '"%')
            ;
        }  
        $qb
           ->andWhere('m.school = :school')
           ->setParameter('school', $school)
        ;

        return $qb->getQuery()->getResult();
   }

   
   public function findSchoolByUserIfActivated($user): ?array
   {
       return $this->createQueryBuilder('m')
            ->where('m.activated = 1')
            ->andWhere('m.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
       ;
   }
}
