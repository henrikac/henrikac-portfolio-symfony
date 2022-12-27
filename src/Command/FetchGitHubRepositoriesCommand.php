<?php

namespace App\Command;

use App\Entity\GitHubRepo;
use App\Repository\GitHubRepoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

#[AsCommand(
    name: 'app:fetch:repositories',
    description: 'Fetches repositories from GitHub',
)]
final class FetchGitHubRepositoriesCommand extends Command
{
    private string $githubUser;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private GitHubRepoRepository $ghr,
        private HttpClientInterface $client
    )
    {
        parent::__construct();

        if (!isset($_ENV['GITHUB_USER']) || strlen($_ENV['GITHUB_USER']) === 0) {
            throw new \RuntimeException('Undefined "GITHUB_USER" environment variable');
        }

        $this->githubUser = $_ENV['GITHUB_USER'];
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->sendRequest(sprintf('https://api.github.com/users/%s/repos', $this->githubUser));

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return Command::FAILURE;
        }

        $fetchedRepositories = $response->toArray();

        while ($next = $this->getNextUrl($response->getHeaders())) {
            $response = $this->sendRequest($next);

            if ($response->getStatusCode() !== Response::HTTP_OK) {
                return Command::FAILURE;
            }

            $fetchedRepositories = array_merge($fetchedRepositories, $response->toArray());
        }

        $repositories = $this->ghr->findAll();
        $repositoryMap = [];

        foreach ($repositories as $repository) {
            $found = false;

            foreach ($fetchedRepositories as $fetchedRepository) {
                $id = $fetchedRepository['id'];

                if ($repository->getGithubId() === $id) {
                    $repositoryMap[$repository->getGithubId()] = $repository;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $this->entityManager->remove($repository);
            }
        }

        foreach ($fetchedRepositories as $fetchedRepository) {
            $id = $fetchedRepository['id'];
            $title = $fetchedRepository['name'];
            $language = $fetchedRepository['language'];
            $url = $fetchedRepository['html_url'];
            $description = $fetchedRepository['description'];
            $stars = $fetchedRepository['stargazers_count'];

            if (array_key_exists($id, $repositoryMap)) {
                $repositoryMap[$id]
                    ->setTitle($title)
                    ->setlanguage($language)
                    ->setUrl($url)
                    ->setDescription($description)
                    ->setStars($stars);
            } else {
                $newRepository = (new GitHubRepo())
                    ->setGithubId($id)
                    ->setTitle($title)
                    ->setlanguage($language)
                    ->setUrl($url)
                    ->setDescription($description)
                    ->setStars($stars)
                    ->setIsPublic(false);

                $this->entityManager->persist($newRepository);
            }
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }

    private function sendRequest(string $url): ResponseInterface
    {
        return $this->client->request(
            'GET',
            $url,
            [
                'headers' => [
                    'Accept' => 'application/vnd.github.v3+json',
                ],
            ],
        );
    }

    private function getNextUrl(array $headers): ?string
    {
        if (!isset($headers['link']) || count($headers['link']) === 0) {
            return null;
        }

        if (!str_contains($headers['link'][0], 'rel="next"')) {
            return null;
        }

        $links = explode(", ", $headers['link'][0]);

        foreach ($links as $link) {
            if (str_contains($link, 'rel="next"')) {
                preg_match('/^<(?P<url>.*)>; rel="next"$/', $link, $output);

                if (isset($output['url'])) {
                    return $output['url'];
                }
            }
        }

        return null;
    }
}