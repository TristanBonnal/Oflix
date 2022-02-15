<?php

namespace App\Tests\Front;

use App\Repository\UserRepository;
use App\Tests\CoreTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class MovieTest extends CoreTest
{
    public function testMovieHome(): void
    {
        // je crée le client HTTP
        $client = static::createClient();
        
        // Je crée une requete HTTP de test sur la route '/'
        $crawler = $client->request('GET', '/');

        // Je vérifie que j'ai une réponse HTTP 200 (Succesful)
        $this->assertResponseIsSuccessful();

        // Je vérifie que dans ma réponse HTML, j'ai bien mon titre de page
        $this->assertSelectorTextContains('h1', 'Films, séries TV et popcorn en illimité.');
    }

    /**
     * Tester les route interdite à un user
     * 
     * avec l'annotation dataProvider et un nom de function
     * cela permet de remplir la valeur de $url avec 
     * @dataProvider getUrls
     */
    public function testRoleUserForbidden($url)
    {
        // Objectif : tester qu'un utilisateur Connecté NE puisse PAS voir le backoffice
        
        // je crée le client HTTP
        $client = static::createClient();

        // Je veux utilisateur, je demande donc à UserRepository
        // Pour obtenir un service userRepository, je demande au conteneur de service
        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findBy(['email' => 'user@user.com']);
        //! findBy renvoit un tableau
        $testuser = $user[0];

        // je demande à mon framework de connecter l'utilisateur
        $client->loginUser($testuser);
        
        //dump($url);
        $response = $client->request('GET',$url);

        // https://symfony.com/doc/current/testing.html#testing-the-response-assertions
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        
    }


    public function testRoleAdminBackMovie()
    {
        // Objectif : tester qu'un utilisateur Connecté NE puisse PAS voir le backoffice
        
        // je crée le client HTTP
        $client = static::createClient();

        // Je veux utilisateur, je demande donc à UserRepository
        // Pour obtenir un service userRepository, je demande au conteneur de service
        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findBy(['email' => 'user@user.com']);
        //! findBy renvoit un tableau
        $testuser = $user[0];

        // je demande à mon framework de connecter l'utilisateur
        $client->loginUser($testuser);

        $response = $client->request('GET','/back/movie');

        // https://symfony.com/doc/current/testing.html#testing-the-response-assertions
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);


        /***** Je change d'utilisateur et je teste la même route ******/

        $user = $userRepository->findBy(['email' => 'admin@admin.com']);
        //! findBy renvoit un tableau
        $testuser = $user[0];

        // je demande à mon framework de connecter l'utilisateur
        $client->loginUser($testuser);
        // si je regarde la route avec un debug:router j'ai '/back/movie/'
        // je vais voir dans le controller Back\MovieController
        // j'ai une annotation sur la classe : '/back/movie'
        // et une annotation sur la route '/'
        // donc la route connue du FW est '/back/movie/'
        $response = $client->request('GET','/back/movie/');

        $this->assertResponseIsSuccessful();

    }

    public function getUrls()
    {
        yield ['/back/movie/'];
        yield ['/back/casting/'];
        // ajouter d'autre URL si
    }

}
