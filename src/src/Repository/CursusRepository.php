<?php

namespace App\Repository;

use App\Entity\Cursus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cursus>
 *
 * @method Cursus|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cursus|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cursus[]    findAll()
 * @method Cursus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CursusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cursus::class);
    }

    public function save(Cursus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Cursus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllOrdered(): array
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->orderBy('c.niveau');

        return $queryBuilder->getQuery()->getArrayResult();
    }

    public function findAllNiveaux(): array
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->select('c.niveau');
        $queryBuilder->groupBy('c.niveau');
        $queryResult = $queryBuilder->getQuery()->getArrayResult();

        // enregistrer le tableau, en mettant les valeurs dans les cléfs
        // pour etre utiliser par symfony
        $result = array();
        $result['Tous niveaux'] = 'Tous';   // ajout manuel d'un choix universel
        for($i = 0; $i < count($queryResult); $i++) {
            $niv = $queryResult[$i]['niveau'];
            $result[$niv] = $niv;
        }

        return $result;
    }


//    /**
//     * @return Cursus[] Returns an array of Cursus objects
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

//    public function findOneBySomeField($value): ?Cursus
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
