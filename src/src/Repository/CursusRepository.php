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

    public function findAllNom(): array
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->select('c.nom');
        $queryBuilder->groupBy('c.nom');
        $queryResult = $queryBuilder->getQuery()->getArrayResult();

        // enregistrer le tableau, en mettant les valeurs dans les cléfs
        // pour etre utiliser par symfony
        $result = array();
        $result['Tous'] = 'Tous';   // ajout manuel d'un choix universel
        for($i = 0; $i < count($queryResult); $i++) {
            $nom = $queryResult[$i]['nom'];
            $result[$nom] = $nom;
        }

        return $result;
    }

    /*
     * Cette fonciton renvoit un tableau de tableau
     * le premier niveau ne contient que 2 élément. Un élément nom et un élément niveau
     * Cette fonction permet d'économiser une requete SQL
     */
    public function findAllNomEtNiveau(): array
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->select('c.nom', 'c.niveau');
        $queryResult = $queryBuilder->getQuery()->getArrayResult();

        // enregistrer les noms et niveaux dans deux tableaux à l'intérieur d'un seul
        $result = array();
        $result['noms']['Tous'] = 'Tous';   // ajout manuel d'un choix universel
        $result['niveaux']['Tous'] = 'Tous';
        for($i = 0; $i < count($queryResult); $i++) {
            $niv = $queryResult[$i]['niveau'];
            $nom = $queryResult[$i]['nom'];
            $result['niveaux'][$niv] = $niv;
            $result['noms'][$nom] = $nom;
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
