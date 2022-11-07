<?php

namespace App\Repository;

use App\Entity\Salle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;


/**
 * @extends ServiceEntityRepository<Salle>
 *
 * @method Salle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Salle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Salle[]    findAll()
 * @method Salle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salle::class);
    }

    public function save(Salle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Salle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /*public function myFindWithPaging($currentPage, $nbPerPage, $filtre, $ordre): Paginator{
        $query = $this->createQueryBuilder('s')
            ->addOrderBy('s'.".".$filtre, $ordre)
            ->getQuery()
            ->setFirstResult(($currentPage - 1) * $nbPerPage)
            ->setMaxResults($nbPerPage);
        return new Paginator($query);
    }


    public function myFindAllWithPaging($currentPage, $nbPerPage): Paginator{
        $query = $this->createQueryBuilder('s')
            ->getQuery()
            ->setFirstResult(($currentPage - 1) * $nbPerPage)
            ->setMaxResults($nbPerPage);

        return new Paginator($query);
    }*/

    public function myFindWithPaging($currentPage, $nbPerPage, $filtre): mixed{
        $cat = substr($filtre, 0, strpos($filtre, '_'));
        $ordre = substr($filtre, strlen($cat) + 1, strpos($filtre, '_') + 1);
        $query = $this->createQueryBuilder('s')
            ->addOrderBy('s'.".".$cat, $ordre)
            ->getQuery();

        return $query->getResult();
    }

    public function myFindAllWithPaging($currentPage, $nbPerPage): mixed
    {
        $query = $this->createQueryBuilder('s')
            ->getQuery();

        return $query->getResult();
    }


//    /**
//     * @return SalleFixtures[] Returns an array of SalleFixtures objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SalleFixtures
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
