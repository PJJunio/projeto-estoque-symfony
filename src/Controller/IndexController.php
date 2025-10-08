<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route(path: '/app', name: 'app_index', methods: 'GET')]
    public function index()
    {
        return $this->render('user/index.html.twig');
    }
}
