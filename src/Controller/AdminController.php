<?php

namespace App\Controller;

use App\Entity\GitHubRepo;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
#[IsGranted('ROLE_SUPERUSER')]
final class AdminController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PaginatorInterface $paginator,
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->redirectToRoute('admin_portfolio_list');
    }

    #[Route('/portfolio', name: 'portfolio_list', methods: ['GET'])]
    public function listPortfolio(Request $request): Response
    {
        $dql = sprintf("SELECT r FROM %s r", GitHubRepo::class);
        $query = $this->entityManager->createQuery($dql);

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/portfolio/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}