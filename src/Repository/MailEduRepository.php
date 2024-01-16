<?php

namespace App\Repository;

use App\Entity\MailEdu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MailEdu>
 *
 * @method MaiEdu|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaiEdu|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaiEdu[]    findAll()
 * @method MaiEdu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailEduRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MailEdu::class);
    }

//    /**
//     * @return MaiEdu[] Returns an array of MaiEdu objects
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

//    public function findOneBySomeField($value): ?MaiEdu
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
