<?php

namespace App\Repository;

use App\Entity\StatutEnseignant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StatutEnseignant>
 *
 * @method StatutEnseignant|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatutEnseignant|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatutEnseignant[]    findAll()
 * @method StatutEnseignant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatutEnseignantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatutEnseignant::class);
    }

    public function save(StatutEnseignant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(StatutEnseignant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAllForm(): mixed
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->select('s');
        $queryBuilder->orderBy('s.nom', 'ASC');
        $queryResult = $queryBuilder->getQuery()->getArrayResult();

        // enregistrer le tableau, en mettant les valeurs dans les cléfs
        // pour etre utiliser par symfony
        $result = array();
        for($i = 0; $i < count($queryResult); $i++) {
            $niv = $queryResult[$i]['nom'];
            $result[$niv] = $queryResult[$i]['id'];
        }

        return $result;
    }

//    /**
//     * @return StatutEnseignant[] Returns an array of StatutEnseignant objects
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

//    public function findOneBySomeField($value): ?StatutEnseignant
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
