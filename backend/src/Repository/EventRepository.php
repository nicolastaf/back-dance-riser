<?php

namespace App\Repository;

use App\Entity\Event;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function add(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Event[] Returns an array of Event objects
    */
   public function findLastCreatedDate(): array
   {
        $today = new DateTime();
        return $this->createQueryBuilder('e')
            ->andWhere('e.date >= :today')
            ->setParameter('today', $today)
            ->orderBy('e.date', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
   }

   public function findFutureEvents(): ?array
   {
        $today = new DateTime();
        return $this->createQueryBuilder('e')
            ->andWhere('e.date >= :today')
            ->setParameter('today', $today)
            ->getQuery()
            ->getResult()
        ;
   }
}
