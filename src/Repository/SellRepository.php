<?php

namespace App\Repository;

use App\Entity\Sell;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sell>
 */
class SellRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sell::class);
    }

    private function convertBrazilianCurrency(?string $value): string
    {
        if (empty($value)) {
            return '0.00';
        }

        $value = trim($value);
        
        $value = str_replace('.', '', $value);
        
        $value = str_replace(',', '.', $value);
        
        return number_format((float) $value, 2, '.', '');
    }

    public function createSell($costumer ,$sellDate, $product, $amount)
    {
        $sell = new Sell();

        $sell->setCostumer($costumer);
        $dateObject = DateTime::createFromFormat("Y-m-d", $sellDate);
        $sell->setDate($dateObject);
        $sell->setProduct($product);
        $sell->setAmount($amount);

        $this->getEntityManager()->persist($sell);
        $this->getEntityManager()->flush();

        return true;
    }

//    /**
//     * @return Sell[] Returns an array of Sell objects
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

//    public function findOneBySomeField($value): ?Sell
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
