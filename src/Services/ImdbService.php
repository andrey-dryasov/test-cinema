<?php
declare(strict_types=1);

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImdbService
{
    private const HEADER_KEY = 'x-rapidapi-key';

    private HttpClientInterface $client;

    private string $url;

    private string $key;

    public function __construct(HttpClientInterface $client, string $imdbApiUrl, string $imdbApiKey)
    {
        $this->client = $client;
        $this->url = $imdbApiUrl;
        $this->key = $imdbApiKey;
    }

    public function findPosterURL(string $title): ?string
    {
        $response = $this->client->request(
            Request::METHOD_GET,
            $this->url,
            [
                'query' => [
                    'q' => $title,
                ],
                'headers' => [
                    self::HEADER_KEY => $this->key,
                ],
            ]
        );

        $statusCode = $response->getStatusCode();

        if ($statusCode !== Response::HTTP_OK) {
            return null;
        }

        $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $poster = $content['results'][0]['image']['url'] ?? null;

        if (!$poster) {
            $poster = $content['results'][0]['parentTitle']['image']['url'] ?? null;
        }

        return $poster;
    }
}