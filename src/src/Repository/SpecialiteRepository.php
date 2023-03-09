<?php

namespace App\Repository;

use App\Entity\Specialite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Specialite>
 *
 * @method Specialite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Specialite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Specialite[]    findAll()
 * @method Specialite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecialiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Specialite::class);
    }

    public function save(Specialite $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Specialite $entity, bool $flush = false): void
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
//     * @return Specialite[] Returns an array of Specialite objects
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

//    public function findOneBySomeField($value): ?Specialite
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
