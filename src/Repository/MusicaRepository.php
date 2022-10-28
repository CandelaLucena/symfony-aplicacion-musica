<?php

namespace App\Repository;

use App\Entity\Musica;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Musica>
 *
 * @method Musica|null find($id, $lockMode = null, $lockVersion = null)
 * @method Musica|null findOneBy(array $criteria, array $orderBy = null)
 * @method Musica[]    findAll()
 * @method Musica[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MusicaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Musica::class);
    }

    public function save(Musica $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Musica $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByName($text): array
    {
        $qb = $this->createQueryBuilder('c')
        ->andWhere('c.nombre LIKE :text')
        ->setParameter('text', '%' . $text . '%')
        ->getQuery();
        return $qb->execute();
    }

//    /**
//     * @return Musica[] Returns an array of Musica objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Musica
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
