<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository
    ) {
    }

    #[Route(path: '/app', name: 'app_index', methods: 'GET')]
    public function index()
    {
        return $this->render('user/index.html.twig', ['products' => $listProducts = $this->productRepository->findBy(['status' => true])]);
    }
}
