<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

       public function getProductBySubcategory($value, $limit): ?Product
       {
           return $this->createQueryBuilder('p')
               ->addSelect('sb')
               ->leftJoin('p.subcategory', 'sb')
               ->andWhere('p.subcategory = :val')
               ->setParameter('val', $value)
               ->setMaxResults($limit)
               ->getQuery()
               ->getOneOrNullResult()
           ;
       }

       public function getProductByPromotion(bool $value): ?Product
       {
           return $this->createQueryBuilder('p')
               ->andWhere('p.iscompleted = :val')
               ->setParameter('val', $value)
               ->orderBy('p.id', 'DESC')
               ->getQuery()
               ->getOneOrNullResult()
           ;
       }
}
