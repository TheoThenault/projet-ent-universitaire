<?php

namespace App\Repository;

use App\Entity\Enseignant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Enseignant>
 *
 * @method Enseignant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enseignant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enseignant[]    findAll()
 * @method Enseignant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnseignantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enseignant::class);
    }

    public function save(Enseignant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Enseignant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllWithSort(): mixed
    {
        $qb = $this->createQueryBuilder("enseignant");
        $qb
            ->select("enseignant")
            ->leftJoin('enseignant.personne', 'personne')
            ->select('personne')
            ->leftJoin('enseignant.StatutEnseignant', 'statutEnseignant')
            ->select('statutEnseignant');
        return $qb->getQuery()->getResult();
    }

    /**
     * @param QueryBuilder $qb
     * @param string $orderBy
     * @param string $asc_or_desc
     * @param string $table
     * @param string|null $table_to_join (option)
     * @return void
     */
    public function sortAscOrDesc(
        QueryBuilder $qb, string $orderBy, string $asc_or_desc, string $table, string $table_to_join = null): void
    {
        $asc_or_desc = strtoupper($asc_or_desc);
        if ($table != null) {
            $qb
                ->leftJoin($table.".".$table_to_join, $table_to_join)
                ->addSelect($table_to_join)
                ->orderBy($table_to_join.".".$orderBy, $asc_or_desc);
        } else {
            $qb->orderBy($table.".".$orderBy, $asc_or_desc);
        }
    }

    public function sortByNameAscOrDesc($asc_or_desc): mixed
    {
        $table = "enseignant";
        $qb = $this->createQueryBuilder($table);
        $this->sortAscOrDesc($qb,"nom",$asc_or_desc, $table,"personne");
        return $qb->getQuery()->getResult();
    }

    public function sortByPrenomAscOrDesc($asc_or_desc): mixed
    {
        $table = "enseignant";
        $qb = $this->createQueryBuilder($table);
        $this->sortAscOrDesc($qb,"prenom",$asc_or_desc, $table,"personne");
        return $qb->getQuery()->getResult();
    }

    public function sortByEMailAscOrDesc($asc_or_desc): mixed
    {
        $table = "enseignant";
        $qb = $this->createQueryBuilder($table);
        $this->sortAscOrDesc($qb,"email",$asc_or_desc, $table,"personne");
        return $qb->getQuery()->getResult();
    }

    public function filterByStatut($choix_statut): mixed
    {
        $qb = $this->createQueryBuilder("enseignant");
        dump($choix_statut);
        $qb
            ->select("enseignant")
            ->leftJoin('enseignant.personne', 'personne')
            ->leftJoin('enseignant.StatutEnseignant', 'statutEnseignant')
            ->where('statutEnseignant.nom = :name')
            ->setParameter(':name', $choix_statut);

        return $qb->getQuery()->getResult();
    }

    public function findByNomOrPrenom($entry): mixed
    {
        if(strlen($entry) < 3)
        {
            return null;
        }

        $queryBuilder = $this->createQueryBuilder('prof');
        $queryBuilder->addSelect('prof');
        $queryBuilder->leftJoin('prof.personne', 'pers');
        $queryBuilder->addSelect('pers');

        $queryBuilder->where('pers.nom like \':entry%\'');
        $queryBuilder->where('pers.prenom like \':entry%\'');

        return $queryBuilder->getQuery()->getResult();
    }

//    /**
//     * @return Enseignant[] Returns an array of Enseignant objects
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

//    public function findOneBySomeField($value): ?Enseignant
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
