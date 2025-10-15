<?php

namespace App\Controller;

use App\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/register', name: 'app_register_view', methods: 'GET')]
    public function createUserView(Request $request, UserRepository $userRepository)
    {
        return $this->render('user/auth/register.html.twig');
    }

    #[Route(path: '/register', name: 'app_register', methods: 'POST')]
    public function createUser(Request $request, UserRepository $userRepository)
    {
        $name = $request->request->get("name");
        $email = $request->request->get("email");
        $password = $request->request->get("password");

        if (!$name || !$email || !$password) {
            $this->addFlash('danger', 'Preencha todos os campos!');
            return $this->redirectToRoute('app_register_view');
        }

        if (strlen($password) < 6) {
            $this->addFlash('danger', 'A senha deve ter no mínimo 6 dígitos!');
            return $this->redirectToRoute('app_register_view');
        }

        if ($userRepository->createUser($name, $email, $password)) {
            $this->addFlash('success', 'Usuário criado com sucesso!');
            return $this->redirectToRoute('app_login');
        }

        $this->addFlash('danger', 'Erro ao criar o usuário!');
            return $this->redirectToRoute('app_register_view');
    }
}
