<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/admin/auth', name: 'admin_auth_')]
class AdminAuthenticationController extends AbstractController
{
    #[Route(path: '/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('admin_portfolio_list');
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('admin/auth/login.html.twig', [
            'error' => $error
        ]);
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
    }
}
