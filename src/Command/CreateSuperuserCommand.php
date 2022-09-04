<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create:superuser',
    description: 'Creates a superuser',
)]
final class CreateSuperuserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $ur,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $io->ask('Email', validator: function ($email) {
            if (is_null($email)) {
                throw new \RuntimeException('Email cannot be empty');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException(sprintf('"%s" is not a valid email', $email));
            }

            $user = $this->ur->findOneBy(['email' => $email]);

            if (!is_null($user)) {
                throw new \RuntimeException(sprintf('A user with email "%s" already exists', $email));
            }

            return $email;
        });

        $password = $io->askHidden('Password', function ($password) {
            if (is_null($password) || strlen($password) === 0) {
                throw new \RuntimeException('Password cannot be empty');
            }

            return $password;
        });

        $confirmPassword = $io->askHidden('Confirm password', function ($confirmPassword) {
            if (is_null($confirmPassword) || strlen($confirmPassword) === 0) {
                throw new \RuntimeException('Please confirm the password');
            }

            return $confirmPassword;
        });

        if (strcmp($password, $confirmPassword) !== 0) {
            $io->error('Password did not match');
            return Command::FAILURE;
        }

        $user = new User();

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $password
        );

        $user
            ->setEmail($email)
            ->setPassword($hashedPassword)
            ->setRoles(['ROLE_SUPERUSER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('Superuser was successfully created!');

        return Command::SUCCESS;
    }
}