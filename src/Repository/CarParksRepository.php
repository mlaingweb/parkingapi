<?php

namespace App\Repository;

use App\Entity\CarParks;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CarParks>
 *
 * @method CarParks|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarParks|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarParks[]    findAll()
 * @method CarParks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarParksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarParks::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(CarParks $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(CarParks $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getSpaceAvailability(Carbon $startDate, Carbon $endDate, CarParks $carPark) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('spaces', 'spaces');
        $rsm->addScalarResult('bookings', 'bookings');
        $query = $this->_em->createNativeQuery('
        SELECT (
            SELECT COUNT(*)
            FROM spaces
            WHERE car_park_id_id = :carParkId
        ) AS spaces,
        (
            SELECT COUNT(*)
            FROM bookings
            WHERE
                car_park_id_id = :carParkId
                AND start_date <= :startDate
                AND end_date >= :endDate
        ) AS bookings
        ', $rsm);
        $query->setParameter('carParkId', $carPark);
        $query->setParameter('startDate', $startDate->format('Y-m-d'));
        $query->setParameter('endDate', $endDate->format('Y-m-d'));
        try {
            return $query->getOneOrNullResult();
        } catch (\Exception $e) {
            return null;
        }
    }

//    /**
//     * @return CarParks[] Returns an array of CarParks objects
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

//    public function findOneBySomeField($value): ?CarParks
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
