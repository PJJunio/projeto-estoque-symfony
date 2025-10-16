<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository
    ) {
    }
    #[Route(path: '/', name: 'app_homepage', methods: 'GET')]
    public function homepage()
    {
        return $this->redirectToRoute("app_index");
    }

    #[Route(path: '/app', name: 'app_index', methods: 'GET')]
    public function index(Request $request)
    {
        $showInactive = $request->query->get('showInactive', false);

        $status = $showInactive ? false : true;

        $listProducts = $this->productRepository->findBy(['status' => $status]);

        return $this->render('user/index.html.twig', [
            'products' => $listProducts,
            'showInactive' => $showInactive
        ]);
    }
}
