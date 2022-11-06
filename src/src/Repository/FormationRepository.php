<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Formation>
 *
 * @method Formation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formation[]    findAll()
 * @method Formation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    public function save(Formation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Formation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllOrderedByCursusName(): array
    {
        $queryBuilder = $this->createQueryBuilder('f');
        $queryBuilder->leftJoin('f.cursus', 'c');
        $queryBuilder->addSelect('c');
        $queryBuilder->orderBy('c.nom');

        return $queryBuilder->getQuery()->getArrayResult();
    }

    public function findAllByCursusNameAndNiveau($name, $niveau): array
    {
        $queryBuilder = $this->createQueryBuilder('f');
        $queryBuilder->leftJoin('f.cursus', 'c');
        $queryBuilder->addSelect('c');
        if($name != 'Tous')
        {
            $queryBuilder->where('c.nom = :name');
            $queryBuilder->setParameter(':name', $name);
        }
        if($niveau != 'Tous')
        {
            $queryBuilder->andWhere('c.niveau = :niv');
            $queryBuilder->setParameter(':niv', $niveau);
        }
        $queryBuilder->addOrderBy('c.nom');

        return $queryBuilder->getQuery()->getArrayResult();
    }

//    /**
//     * @return Formation[] Returns an array of Formation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Formation
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
