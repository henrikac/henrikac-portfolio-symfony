<?php

namespace App\Controller;

use App\Repository\GitHubRepoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(private GitHubRepoRepository $ghr) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $repositories = $this->ghr->findBy(['isPublic' => true], ['title' => 'ASC']);

        return $this->render('default/index.html.twig', [
            'repositories' => $repositories
        ]);
    }
}