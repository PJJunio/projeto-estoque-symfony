<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    public function __construct(
        private CategoryRepository $categoryRepository,
    ) {
    }

    #[Route(path: '/app/category', name: 'app_category', methods: 'GET')]
    public function category()
    {
        return $this->render('user/category/category.html.twig', ['categories' => $this->categoryRepository->findAll()]);
    }

    #[Route(path: '/app/category/new', name: 'app_category_new', methods: 'GET')]
    public function categoryNew()
    {
        return $this->render('user/category/new_category.html.twig');
    }

    #[Route(path: 'app/category/new', name: 'app_category_create', methods: 'POST')]
    public function createCategory(Request $request)
    {
        $categoryName = $request->request->get('nome');

        if (strlen($categoryName) > 50) {
            $this->addFlash('danger', 'O nome deve ter no máximo 50 caracteres');
            return $this->redirectToRoute('app_category_new');
        }

        if ($this->categoryRepository->findBy(['name' => $categoryName])) {
            $this->addFlash('danger', 'Categoria já existe!');

            return $this->redirectToRoute('app_category_new');
        }

        if ($this->categoryRepository->createCategory($categoryName)) {
            $this->addFlash('success', 'Categoria criada com sucesso!');

            return $this->redirectToRoute('app_category_new');
        }

        return new Response();
    }

    #[Route(path: '/app/category/delete/{id}', name: 'app_category_delete', methods: 'GET')]
    public function deleteCategory($id)
    {
        if ($this->categoryRepository->deleteCategory($id)) {
            $this->addFlash('success', 'Categoria deletada com sucesso!');
            return $this->redirectToRoute('app_category');
        }

        $this->addFlash('error', 'Categoria não encontrada!');
        return $this->redirectToRoute('app_category');
    }

    #[Route(path: '/app/category/edit/{id}', name: 'app_category_edit_view', methods: 'GET')]
    public function editCategoryView($id)
    {
        return $this->render('user/category/edit_category.html.twig', ['categories' => $this->categoryRepository->findOneBy(['id' => $id])]);
    }

    #[Route(path: '/app/category/edit', name: 'app_category_edit', methods: 'POST')]
    public function editCategory(Request $request)
    {
        $categoryName = $request->request->get('nome');
        $categoryId = $request->request->get('id');

        if ($this->categoryRepository->findBy(['name' => $categoryName])) {
            $this->addFlash('danger', 'Categoria já existe!');

            return $this->redirectToRoute('app_category_edit_view');
        }
        
        if ($this->categoryRepository->editCategory($categoryId, $categoryName)) {
            $this->addFlash('success', 'Categoria editada com sucesso!');

            return $this->redirectToRoute('app_category');

        }

        return new Response();
    }
}