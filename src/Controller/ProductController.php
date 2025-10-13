<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private ProductRepository $productRepository
    ) {
    }

    #[Route(path: '/product/new', name: 'app_product_new_view', methods: 'GET')]
    public function newProductView()
    {
        return $this->render('user/product/new_product.html.twig', ['categories' => $this->categoryRepository->findAll()]);
    }

    #[Route(path: '/product/new', name: 'app_product_new', methods: 'POST')]
    public function newProduct(Request $request)
    {
        $productName = $request->request->get('nome');
        $productDescription = $request->request->get('descricao');
        $productValue = $request->request->get('valor');
        $productAmount = $request->request->get('quantidade');
        $productCategory = $request->request->get('categoriaId');

        $category = $this->categoryRepository->find($productCategory);

        if (!$category) {
            $this->addFlash('error', 'Categoria não encontrada!');
            return $this->redirectToRoute('app_product_new_view');
        }

        $this->productRepository->createProduct(
            $productName,
            $productDescription,
            $category,
            $productAmount,
            $productValue
        );

        $this->addFlash('success', 'Produto criado com sucesso!');
        return $this->redirectToRoute('app_product_new_view');
    }

    #[Route(path: '/product/edit/{id}', name: 'app_product_edit_view', methods: 'GET')]
    public function edidProductView($id)
    {
        $product = $this->productRepository->find($id);
        $categories = $this->categoryRepository->findAll();

        return $this->render('user/product/edit_product.html.twig', [
            'categories' => $categories,
            'product' => $product
        ]);
    }

    #[Route(path: '/product/edit', name: 'app_product_edit', methods: 'POST')]
    public function editProduct(Request $request)
    {
        $productId = $request->request->get('productId');
        $productName = $request->request->get('nome');
        $productDescription = $request->request->get('descricao');
        $productValue = $request->request->get('valor');
        $productAmount = $request->request->get('quantidade');
        $productCategory = $request->request->get('categoriaId');

        $category = $this->categoryRepository->find($productCategory);

        if (!$category) {
            $this->addFlash('error', 'Categoria não encontrada!');
            return $this->redirectToRoute('app_product_edit');
        }

        $this->productRepository->editProduct($productId, $productName, $productDescription, $category, $productAmount, $productValue);
        $this->addFlash('success', 'Produto editado com sucesso!');
        return $this->redirectToRoute('app_product_edit');
    }
}
