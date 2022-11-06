<?php

namespace App\Repository;

use App\Entity\Etudiant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Etudiant>
 *
 * @method Etudiant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etudiant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etudiant[]    findAll()
 * @method Etudiant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtudiantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etudiant::class);
    }

    public function save(Etudiant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Etudiant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllByCursusAndFormation($cursus, $formation): array
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $queryBuilder->Select('e');
        $queryBuilder->leftJoin('e.personne', 'p');
        $queryBuilder->addSelect('p');
        $queryBuilder->leftJoin('e.formation', 'f');
        $queryBuilder->addSelect('f');
        $queryBuilder->leftJoin('f.cursus', 'c');
        $queryBuilder->addSelect('c');
        if($cursus != 'Tous')
        {
            $queryBuilder->where('c.nom = :name');
            $queryBuilder->setParameter(':name', $cursus);
        }
        if($formation != 'Tous')
        {
            $queryBuilder->andWhere('f.nom = :for');
            $queryBuilder->setParameter(':for', $formation);
        }
        //$queryBuilder->addOrderBy('c.nom');

        $result = $queryBuilder->getQuery()->getArrayResult();

        return $result;
    }

    public function findAllCursus(): array
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $queryBuilder->leftJoin('e.formation', 'f');
        $queryBuilder->addSelect('f');
        $queryBuilder->leftJoin('f.cursus', 'c');
        $queryBuilder->addSelect('c');
        $queryBuilder->groupBy('c.nom');

        //$queryBuilder->addOrderBy('c.nom');

        $queryResult = $queryBuilder->getQuery()->getArrayResult();
        $result = array();
        $result['Tous'] = 'Tous';   // ajout manuel d'un choix universel
        for($i = 0; $i < count($queryResult); $i++) {
            $nom = $queryResult[$i]['formation']['cursus']['nom'];
            $result[$nom] = $nom;
        }

        return $result;
    }

    public function findAllFormation(): array
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $queryBuilder->leftJoin('e.formation', 'f');
        $queryBuilder->addSelect('f');
        $queryBuilder->groupBy('f.nom');

        //$queryBuilder->addOrderBy('c.nom');

        $queryResult = $queryBuilder->getQuery()->getArrayResult();
        $result = array();
        $result['Tous'] = 'Tous';   // ajout manuel d'un choix universel
        for($i = 0; $i < count($queryResult); $i++) {
            $nom = $queryResult[$i]['formation']['nom'];
            $result[$nom] = $nom;
        }

        return $result;
    }



//    /**
//     * @return Etudiant[] Returns an array of Etudiant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Etudiant
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
