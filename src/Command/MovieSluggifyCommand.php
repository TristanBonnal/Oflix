<?php

namespace App\Command;

use App\Repository\MovieRepository;
use App\Service\MySlugger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieSluggifyCommand extends Command
{
    protected static $defaultName = 'app:movies:sluggify';
    protected static $defaultDescription = 'Slugifies movies titles in the Database';
    private $movieRepository;
    private $mySlugger;
    private $entityManager;

    public function __construct(MovieRepository $movieRepository, MySlugger $mySlugger, EntityManagerInterface $entityManager)
    {
        $this->movieRepository = $movieRepository;
        $this->mySlugger = $mySlugger;
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        // j'utilise une classe qui permet de faire des entrées/sorties 
        $io = new SymfonyStyle($input, $output);
        $io->info('attache ta ceinture, ça commence...');

       $movies = $this->movieRepository->findAll();

        foreach ($movies as $movie) {
            $io->info('Titre à Slugifier : ' . $movie->getTitle());

            // On slugifie le titre avec notre service MySlugger
            $movie->setSlug($this->mySlugger->sluggify($movie->getTitle()));

            $io->info('Résultat : ' . $movie->getSlug());
        }

        $this->entityManager->flush();

        $io->info('déjà terminé !');
        return Command::SUCCESS;
    }
}
