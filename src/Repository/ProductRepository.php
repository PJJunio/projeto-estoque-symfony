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

    public function createProduct($name, $description, $category, $amount, $value)
    {
        $product = new Product();

        $product->setName($name);
        $product->setDescription($description);
        $product->setCategory($category);
        $product->setAmount($amount);
        $product->setValue($value);

        $product->setValue($this->convertBrazilianCurrency($value));

        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->flush();

        return true;
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

    public function editProduct($id, $name, $description, $category, $amount, $value)
    {
        $product = $this->find($id);

        if($product) {
            $product->setName($name);
            $product->setDescription($description);
            $product->setCategory($category);
            $product->setAmount($amount);
            $product->setValue($this->convertBrazilianCurrency($value));

            $this->getEntityManager()->persist($product);
            $this->getEntityManager()->flush();

            return true;
        }

        return false;
    }

    public function inativeProduct($id)
    {
        $product = $this->findOneBy(['id' => $id]);

        if($product){
            $product->setStatus(false);

            $this->getEntityManager()->persist($product);
            $this->getEntityManager()->flush();

            return true;
        }

        return false;
    }
    
    public function activeProduct($id)
    {
        $product = $this->findOneBy(['id' => $id]);

        if($product){
            $product->setStatus(true);

            $this->getEntityManager()->persist($product);
            $this->getEntityManager()->flush();

            return true;
        }

        return false;
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

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
