<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApi 
{
    private $client;
    private $parameter;

    public function __construct(HttpClientInterface $client, ParameterBagInterface $parameter) 
    {
        $this->client = $client;
        $this->parameter = $parameter;
    }


    public function fetchOmdb(string $title): array
    {
        $query = 't=' . $title;
        $response = $this->client->request(
            'GET',
            'http://omdbapi.com/?apikey=' . $this->parameter->get('app.omdb_api_key') . '&' . $query
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return $content;
    }

    public function fetchPoster(string $title): ?string
    {
        $movie = $this->fetchOmdb($title);
        if (!empty($movie) && array_key_exists('Poster', $movie)) {
            return $movie['Poster'];
        } else {
            return null;
        }
    }
}