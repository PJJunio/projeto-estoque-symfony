<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route(path: '/app/category', name: 'app_category', methods: 'GET')]
    public function category()
    {
        return $this->render('user/category/category.html.twig');
    }

    #[Route(path: '/app/category/new', name: 'app_category_new', methods: 'GET')]
    public function categoryNew()
    {
        return $this->render('user/category/new_category.html.twig');
    }
}