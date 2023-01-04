<?php

namespace App\Repository;

use App\Entity\Cour;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cour>
 *
 * @method Cour|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cour|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cour[]    findAll()
 * @method Cour[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cour::class);
    }

    public function save(Cour $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Cour $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllInformations(): array
    {
        $queryBuilder = $this->createQueryBuilder('cr');
        $queryBuilder->addSelect('cr');

        $queryBuilder->leftJoin('cr.ue', 'u');
        $queryBuilder->addSelect('u');

        $queryBuilder->leftJoin('u.formations', 'f');
        $queryBuilder->addSelect('f');

        $queryBuilder->leftJoin('f.cursus', 'c');
        $queryBuilder->addSelect('c');

        $queryBuilder->leftJoin('cr.enseignant', 'e');
        $queryBuilder->addSelect('e');

        $queryBuilder->leftJoin('e.personne', 'p');
        $queryBuilder->addSelect('p');

        return $queryBuilder->getQuery()->getArrayResult();
    }

    public function findAllByChoices($cursus, $formation): array
    {
        $queryBuilder = $this->createQueryBuilder('cour');
        $queryBuilder->addSelect('cour');
	$queryBuilder->addSelect('cour.enseignant');
        $queryBuilder->leftJoin('cour.ue', 'ue');
        $queryBuilder->addSelect('ue');
        $queryBuilder->leftJoin('ue.formations', 'f');
        $queryBuilder->addSelect('f');
        $queryBuilder->leftJoin('f.cursus', 'c');
        $queryBuilder->addSelect('c');
        if($cursus != 'Tous')
        {
            $queryBuilder->andWhere('c.nom = :cur');
            $queryBuilder->setParameter('cur', $cursus);
        }
        if($formation != 'Tous')
        {
            $queryBuilder->andWhere('f.nom = :for');
            $queryBuilder->setParameter('for', $formation);
        }
        $queryBuilder->orderBy('cour.creneau');
	dump($queryBuilder);
        return $queryBuilder->getQuery()->getArrayResult();
    }

//    /**
//     * @return Cour[] Returns an array of Cour objects
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

//    public function findOneBySomeField($value): ?Cour
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
