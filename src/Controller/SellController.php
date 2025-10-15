<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\SellRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


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
        $product = $this->productRepository->findBy(['status' => true]);

        return $this->render('/user/sell/new_sell.html.twig', ['products' => $product]);
    }

    #[Route(path: '/sell/new', name: 'app_sell', methods: ['POST'])]
    public function newSell(Request $request)
    {
        $cpfSubmetido = $request->request->get('cpf_cliente');
        $cpf = preg_replace('/[^0-9]/', '', $cpfSubmetido);
        $sellDate = $request->request->get('data_venda');
        $product = $request->request->get('produto');
        $amount = $request->request->get('quantidade');

        if (empty($cpf) || empty($sellDate) || empty($product) || empty($amount)) {
            $this->addFlash('danger', 'Todos os campos são obrigatórios.');
            return $this->redirectToRoute('app_sell_new_view');
        }

        if (strlen($cpf) != 11) {
            $this->addFlash('danger', 'CPF inválido!');
            return $this->redirectToRoute('app_sell_new_view');
        }

        if ($amount < 0) {
            $this->addFlash('danger', 'Quantidade não pode ser menor que 0!');
            return $this->redirectToRoute('app_sell_new_view');
        }

        $product = $this->productRepository->find($product);

        if (!$product) {
            $this->addFlash('danger', 'Produto inválido ou não encontrado.');
            return $this->redirectToRoute('app_sell_new_view');
        }

        if ($this->sellRepository->createSell($cpf, $sellDate, $product, $amount)) {
            $this->addFlash('success', 'Venda criado com sucesso!');

            return $this->redirectToRoute('app_sell_new_view');
        }

        $this->addFlash('danger', 'Falha ao criar a venda!');
        return $this->redirectToRoute('app_sell_new_view');
    }

    #[Route(path: '/sell/edit/{id}', name: 'app_sell_edit_view', methods: ['GET'])]
    public function editSellView($id)
    {
        $sell = $this->sellRepository->find($id);
        $product = $this->productRepository->findBy(['status' => true]);

        return $this->render('/user/sell/edit_sell.html.twig', ['sell' => $sell, 'products' => $product]);
    }

    #[Route(path: '/sell/edit', name: 'app_sell_edit', methods: ['POST'])]
    public function editSell(Request $request)
    {
        $id = $request->request->get('id');
        $cpfSubmetido = $request->request->get('cpf_cliente');
        $cpf = preg_replace('/[^0-9]/', '', $cpfSubmetido);
        $sellDate = $request->request->get('data_venda');
        $product = $request->request->get('produto');
        $status = $request->request->get('status');
        $amount = $request->request->get('quantidade');

        if (strlen($cpf) != 11) {
            $this->addFlash('danger', 'CPF inválido!');
            return $this->redirectToRoute('app_sell_new_view');
        }

        if ($amount < 0) {
            $this->addFlash('danger', 'Quantidade não pode ser menor que 0!');
            return $this->redirectToRoute('app_sell_new_view');
        }

        if ($this->sellRepository->editSell($id, $cpf, $sellDate, $product, $amount, $status)) {
            $this->addFlash('success', 'Venda editada com sucesso!');
            return $this->redirectToRoute('app_sell_view');
        }

        $this->addFlash('danger', 'Falha ao editar a venda!');
        return $this->redirectToRoute('app_sell_view');
    }

    #[Route(path: '/sell/finish/{id}', name: 'app_sell_finish', methods: ['GET'])]
    public function finishSell($id)
    {
        if (!$this->sellRepository->alterStatus($id)) {
            $this->addFlash('danger', 'Falha ao finalizar venda!');
            return $this->redirectToRoute('app_sell_view');
        }

        $this->addFlash('success', 'Venda finalizada com sucesso!');
        return $this->redirectToRoute('app_sell_view');
    }
}
