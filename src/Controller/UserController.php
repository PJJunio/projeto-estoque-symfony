<?php

namespace App\Controller;

use App\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/register', name: 'app_user_register')]
    public function createUser(Request $request, UserRepository $userRepository)
    {
        return $this->render('user/register.html.twig');

        // $data = $request->toArray();

        // $name = $data['name'] ?? null;
        // $email = $data['email'] ?? null;
        // $password = $data['password'] ?? null;

        // if (!$name || !$email || !$password) {
        //     return $this->json(['error' => 'Dados inválidos ou faltando'], Response::HTTP_BAD_REQUEST);
        // }

        // if (strlen($password) < 6) {
        //     return $this->json(['error' => 'A senha deve ter no mínimo 6 digitos!'], Response::HTTP_BAD_REQUEST);
        // }

        // if ($userRepository->createUser($name, $email, $password)) {
        //     return $this->json(['messae' => 'Usuário criado com sucesso!'], Response::HTTP_OK);
        // }

        // return $this->json(['error' => 'Ocorreu um eror ao criar o usuário!'], Response::HTTP_BAD_REQUEST);
    }
}
