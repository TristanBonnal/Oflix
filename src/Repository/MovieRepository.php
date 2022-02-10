<?php

namespace App\Repository;

use App\Entity\Actor;
use App\Entity\Genre;
use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * @link https://symfony.com/doc/current/doctrine.html#querying-for-objects-the-repository
     * 
     * @return Movie[] Returns an array of Movie objects
    */
    public function findAllOrderedByTitleDQL()
    {
        // le repository ne sais pas faire de DQL
        // on est obligé d'apeller le Manager
        $entityManager = $this->getEntityManager();

        // on utilise le système d'alias pour représenter notre Entity
        // Dnas le select on dit que l'on veut TOUTE l'entité en utilisant l'alias
        $query = $entityManager->createQuery(
            'SELECT m.title
            FROM App\Entity\Movie m
            ORDER BY m.title DESC');

        // returns an array of Product objects
        $resultats = $query->getResult();
    }

    /**
     * @link https://symfony.com/doc/current/doctrine.html#querying-with-the-query-builder
     * 
     * @return Movie[] Returns an array of Movie objects
    */
    public function findAllOrderedByTitle()
    {
        // pour faire une requete je doit donner un nom à la table: un alias
        // comme dans le FROM SQL : FROM table t_alias
        $resultats = $this->createQueryBuilder('m')
            // à partir d'ici j'utilise l'alias pour représenter ma table
            // je tri sur le title de la table
            ->orderBy('m.title', 'DESC')

            // l'avant dernière instruction est de générer la requete
            ->getQuery()
            // et la dernière instruction est d'éxecuter la requete
            // on reçoit donc les résultats à partir de là
            ->getResult();

        return $resultats;
    }

    public function findRandomMovie()
    {
        // ni le repository, ni le manage savent faire du SQL
        // on descend donc d'un cran
        // pour all
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT *, slug FROM movie
            ORDER BY RAND()
            LIMIT 1
            ';
        $results = $conn->executeQuery($sql);

        // returns an array of arrays (i.e. a raw data set)
        return $results->fetchAssociative();
    }

    public function findMovieByGenre(Genre $genre)
    {
        return $this->createQueryBuilder('m')
        ->innerJoin('m.genres', 'g')
        ->where('g.id = :genre_id')
        ->setParameter('genre_id', $genre->getId())
        ->getQuery()->getResult();
    }

    public function findMovieByActor(Actor $actor)
    {
        return $this->createQueryBuilder('m')
        ->innerJoin('m.castings', 'c')
        ->where('c.actor = :actor')
        ->setParameter('actor', $actor->getId())
        ->getQuery()->getResult();
    }

}
