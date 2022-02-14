<?php

namespace App\Tests\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieTest extends WebTestCase
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

    public function testRoleUserReviewAdd()
    {
        // Objectif : tester qu'un utilisateur Connecté puisse voir le bouton "Ajouter un review"
        
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

        // Je vérifie que j'ai une réponse HTTP 200 (Succesful)
        $this->assertResponseIsSuccessful();

        // je cherche le bouton
        // je vais sur la page web, et dans DevTools, sur l'élément > Copy > Copu Selector
        
        $this->assertSelectorTextContains('body > div.container.bg-lighttt.pt-5 > div > p > a', 'Ajouter une critique');
    }

    /**
    * Override PHPUnit fail method
    * to catch "assertResponse" exceptions
    * 
    * @link https://devdocs.io/phpunit~9/fixtures
    */
    protected function onNotSuccessfulTest(\Throwable $t): void
    {
        // If "assertResponse" is found in the trace, custom message
        if (strpos($t->getTraceAsString(), 'assertResponse') > 0) {
            $arrayMessage = explode("\n", $t->getMessage());
            $message = $arrayMessage[0] . "\n" . $arrayMessage[1];
            $this->fail($message);
        }

        // Other Exceptions
        throw $t;
    }
}
