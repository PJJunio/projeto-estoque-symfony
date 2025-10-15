<?php

namespace App\Repository;

use App\Entity\Sell;
use App\Entity\Product;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sell>
 */
class SellRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private ProductRepository $productRepository
    ) {
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

    public function createSell($costumer, $sellDate, $productId, $amount)
    {
        $product = $this->productRepository->find($productId);

        if(!$product) {
            return false;
        }

        if($product->getAmount() < $amount) {
            return false;
        }

        $newAmount = $product->getAmount() - $amount;
        $product->setAmount($newAmount);

        $sell = new Sell();

        $sell->setCostumer($costumer);
        $dateObject = DateTime::createFromFormat("Y-m-d", $sellDate);
        $sell->setDate($dateObject);
        $sell->setProduct($product);
        $sell->setAmount($amount);

        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->persist($sell);
        $this->getEntityManager()->flush();

        return true;
    }

    public function editSell($id, $costumer, $sellDate, $productId, $amount, $status)
    {
        $product = $this->productRepository->find($productId);
        $sell = $this->find($id);

        if(!$product || !$sell) {
            return false;
        }
        
        if($amount < $sell->getAmount()) {
            $newAmount = $product->getAmount() + ($sell->getAmount() - $amount);
            $product->setAmount($newAmount);
        }

        if($amount > $sell->getAmount()) {
            $newAmount = $amount - $sell->getAmount();
            $product->setAmount($newAmount);
        }

        if ($sell) {
            $sell->setCostumer($costumer);
            $dateObject = DateTime::createFromFormat("Y-m-d", $sellDate);
            $sell->setDate($dateObject);
            $sell->setProduct($product);
            $sell->setAmount($amount);
            $sell->setStatus($status);
            $sell->setUpdatedAt(new DateTimeImmutable());

            $this->getEntityManager()->persist($product);
            $this->getEntityManager()->persist($sell);
            $this->getEntityManager()->flush();

            return true;
        }

        return false;
    }

    public function alterStatus($id)
    {
        $sell = $this->find($id);

        if($sell) {
            $sell->setStatus(false);

            $this->getEntityManager()->persist($sell);
            $this->getEntityManager()->flush();

            return true;
        }

        return false;
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
