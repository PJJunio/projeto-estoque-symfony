<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route(path: '/product/new', name: 'app_product_new_view', methods: 'GET')]
    public function newProductView()
    {
        return $this->render('user/product/product.html.twig');
    }

}
