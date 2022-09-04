<?php

namespace App\Controller;

use App\Entity\GitHubRepo;
use App\Repository\GitHubRepoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/portfolio', name: 'portfolio_')]
#[IsGranted('ROLE_SUPERUSER')]
class PortfolioController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private GitHubRepoRepository $ghr,
        private PaginatorInterface $paginator,
    ) {
    }

    #[Route('', name: 'list', methods: ['GET'])]
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

    #[Route('/toggle-repository-visibility/{id}', name: 'toggle_repository_visibility', methods: ['POST'])]
    public function toggleRepositoryVisibility(int $id): JsonResponse
    {
        $repository = $this->ghr->findOneBy(['id' => $id]);

        if (is_null($repository)) {
            return new JsonResponse(
                sprintf('Found no repository with id: %d', $id),
                status: Response::HTTP_NOT_FOUND
            );
        }

        $repository->setIsPublic(!$repository->isPublic());

        $this->entityManager->flush();

        return new JsonResponse();
    }
}
