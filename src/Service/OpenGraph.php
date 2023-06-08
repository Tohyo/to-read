<?php

namespace App\Service;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenGraph
{
    public function __construct(
        public HttpClientInterface $client
    ) {
    }

    public function __invoke(string $url)
    {
        $crawler = new Crawler($this->client->request('GET', $url)->getContent());

        return [
            'title' => $crawler->filter('meta[property="og:title"]')->attr('content'),
        ];
    }
}