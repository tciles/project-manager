<?php

namespace App\Adapter;

use App\Entity\Project;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GithubApiAdapter implements ApiAdapterInterface
{
    private const BASE_URL = 'https://api.github.com';

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getRepository(Project $project): array
    {
        $url = sprintf("%s/repos/%s", self::BASE_URL, $project->getFullname());
        $response = $this->client->request('GET', $url);
        return $response->toArray();
    }

    public function getReleases(Project $project): array
    {
        $url = sprintf("%s/repos/%s/releases", self::BASE_URL, $project->getFullname());
        $response = $this->client->request('GET', $url);
        return $response->toArray();
    }
}
