<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\SellRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Vtiful\Kernel\Format;


class SellController extends AbstractController
{
    public function __construct(
        private SellRepository $sellRepository,
        private ProductRepository $productRepository
    ) {
    }

    #[Route(path: '/sell', name: 'app_sell_view', methods: ['GET'])]
    public function sellView()
    {
        $sells = $this->sellRepository->findAll();

        return $this->render('/user/sell/sell.html.twig', ["sells" => $sells]);
    }

    #[Route(path: '/sell/new', name: 'app_sell_new_view', methods: ['GET'])]
    public function newSellView()
    {
        $product = $this->productRepository->findAll();

        return $this->render('/user/sell/new_sell.html.twig', ['products' => $product]);
    }

    #[Route(path: '/sell/new', name: 'app_sell', methods: ['POST'])]
    public function newSell(Request $request)
    {
        $costumer = $request->request->get('cpf_cliente');
        $sellDate = $request->request->get('data_venda');
        $product = $request->request->get('produto');
        $amount = $request->request->get('quantidade');

        if (empty($costumer) || empty($sellDate) || empty($product) || empty($amount)) {
            $this->addFlash('danger', 'Todos os campos são obrigatórios.');
            return $this->redirectToRoute('app_sell_new_view');
        }
        
        $product = $this->productRepository->find($product);

        if (!$product) {
            $this->addFlash('danger', 'Produto inválido ou não encontrado.');
            return $this->redirectToRoute('app_sell_new_view');
        }

        $this->sellRepository->createSell($costumer, $sellDate, $product, $amount);

        $this->addFlash('success', 'Venda criado com sucesso!');
        return $this->redirectToRoute('app_sell_new_view');
    }

    #[Route(path: '/sell/edit', name: 'app_sell_edit_view', methods: ['GET'])]
    public function editSellView()
    {
        return $this->render('/user/sell/edit_sell.html.twig');
    }
}
