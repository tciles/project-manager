<?php

namespace App\Resolver;

use App\Adapter\ApiAdapterInterface;
use App\Adapter\GithubApiAdapter;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiClientResolver implements ApiClientResolverInterface
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function resolve(?string $url = null): ApiAdapterInterface
    {
        if (strpos($url, 'gitlab')) {
            throw new \RuntimeException('Gitlab Api Adapter does not yet implemented');
        }

        if (strpos($url, 'github')) {
            return new GithubApiAdapter($this->client);
        }

        throw new \RuntimeException('Api Adapter does not yet implemented');
    }
}
