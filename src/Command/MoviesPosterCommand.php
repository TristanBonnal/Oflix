<?php

namespace App\Command;

use App\Repository\MovieRepository;
use App\Service\OmdbApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MoviesPosterCommand extends Command
{
    protected static $defaultName = 'app:movies:poster';
    protected static $defaultDescription = 'Add a short description for your command';
    private $movieRepository;
    private $entityManager;

    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $entityManager, OmdbApi $api)
    {
        $this->movieRepository = $movieRepository;
        $this->entityManager = $entityManager;
        $this->entityManager = $entityManager;
        $this->api = $api;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $movies = $this->movieRepository->findAll();
        foreach ($movies as $movie) {
            $io->info($movie->getTitle());
            $poster = $this->api->fetchPoster($movie->getTitle());
            if ($poster) {
                $movie->setPoster($poster);
            }

        }
        $this->entityManager->flush();
        $io->success('Success !! :)');

        return Command::SUCCESS;
    }
}
