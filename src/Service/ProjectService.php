<?php

namespace App\Service;

use App\Adapter\GithubApiAdapter;
use App\Entity\Project;
use App\Entity\ProjectVersion;
use App\Repository\ProjectRepository;
use App\Repository\ProjectVersionRepository;

class ProjectService
{
    private ProjectRepository $projectRepository;

    private ProjectVersionRepository $projectVersionRepository;

    private GithubApiAdapter $client;

    public function __construct(
        ProjectRepository $projectRepository,
        ProjectVersionRepository $projectVersionRepository,
        GithubApiAdapter $client
    ) {
        $this->projectRepository = $projectRepository;
        $this->projectVersionRepository = $projectVersionRepository;
        $this->client = $client;
    }

    public function synchronizeProjectVersions(): void
    {
        $projects = $this->projectRepository->findProjectsForVersionsSynchronisation();

        foreach ($projects as $project) {
            $this->syncReleasesForProject($project);
        }
    }

    private function syncReleasesForProject(Project $project): void
    {
        $response = $this->client->getReleases($project);

        if (empty($response) || !\is_array($response)) {
            return;
        }

        foreach($response as $release) {
            if (!isset($release['tag_name'])) {
                continue;
            }

            $this->addReleaseForProject($project, $release);
        }
    }

    private function addReleaseForProject(Project $project, array $release): void {
        $projectVersion = $this->projectVersionRepository->findOneBy([
            'active' => true,
            'name' => $release['tag_name'],
            'project' => $project,
        ]);

        if (!($projectVersion instanceof ProjectVersion)) {
            $projectVersion = new ProjectVersion();
            $projectVersion->setProject($project)
                ->setName($release['tag_name'])
                ->setUrl($release['html_url']);

            $this->projectVersionRepository->persist($projectVersion);
        } else {
            $projectVersion->setUrl($release['html_url']);
        }

        $this->projectVersionRepository->flush();
    }
}
