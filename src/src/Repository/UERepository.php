<?php

namespace App\Repository;

use App\Entity\UE;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UE>
 *
 * @method UE|null find($id, $lockMode = null, $lockVersion = null)
 * @method UE|null findOneBy(array $criteria, array $orderBy = null)
 * @method UE[]    findAll()
 * @method UE[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UERepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UE::class);
    }

    public function save(UE $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UE $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllSpecialite(): array
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder->leftJoin('u.specialite', 's');
        $queryBuilder->addSelect('s.nom');
        $queryBuilder->groupBy('s.nom');
        $queryBuilder->orderBy('s.nom');
        dump($queryBuilder);
        $queryResult = $queryBuilder->getQuery()->getArrayResult();

        $result = array();
        $result['Toutes spécialités'] = 'Tous';   // ajout manuel d'un choix universel
        for($i = 0; $i < count($queryResult); $i++) {
            $nom = $queryResult[$i]['nom'];
            $result[$nom] = $nom;
        }
        return $result;
    }


    public function findAllByChoices($specialite, $cursus, $formation): array
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder->addSelect('u');
        $queryBuilder->leftJoin('u.specialite', 's');
        $queryBuilder->addSelect('s');
        $queryBuilder->leftJoin('u.formation', 'f');
        $queryBuilder->addSelect('f');
        $queryBuilder->leftJoin('f.cursus', 'c');
        $queryBuilder->addSelect('c');
        if($specialite != 'Tous')
        {
            $queryBuilder->andWhere('s.nom = :spe');
            $queryBuilder->setParameter('spe', $specialite);
        }
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
        $queryBuilder->orderBy('u.nom');
        return $queryBuilder->getQuery()->getArrayResult();;
    }

    public function findAllOrdered(): array
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder->addSelect('u');
        $queryBuilder->leftJoin('u.specialite', 's');
        $queryBuilder->addSelect('s');
        $queryBuilder->leftJoin('u.formation', 'f');
        $queryBuilder->addSelect('f');
        $queryBuilder->leftJoin('f.cursus', 'c');
        $queryBuilder->addSelect('c');
        $queryBuilder->orderBy('c.nom');

        return $queryBuilder->getQuery()->getArrayResult();
    }

//    /**
//     * @return UE[] Returns an array of UE objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UE
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
