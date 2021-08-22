<?php

namespace App\Service;

use App\Entity\Project;
use App\Entity\ProjectVersion;
use App\Repository\ProjectRepository;
use App\Repository\ProjectVersionRepository;
use App\Resolver\ApiClientResolverInterface;

use function is_array;

class ProjectService
{
    /**
     * @var ProjectRepository
     */
    private ProjectRepository $projectRepository;

    /**
     * @var ProjectVersionRepository
     */
    private ProjectVersionRepository $projectVersionRepository;

    /**
     * @var ApiClientResolverInterface
     */
    private ApiClientResolverInterface $apiClientResolver;

    /**
     * @param ProjectRepository $projectRepository
     * @param ProjectVersionRepository $projectVersionRepository
     * @param ApiClientResolverInterface $apiClientResolver
     */
    public function __construct(
        ProjectRepository $projectRepository,
        ProjectVersionRepository $projectVersionRepository,
        ApiClientResolverInterface $apiClientResolver
    ) {
        $this->projectRepository = $projectRepository;
        $this->projectVersionRepository = $projectVersionRepository;
        $this->apiClientResolver = $apiClientResolver;
    }

    /**
     *
     */
    public function synchronizeProjectVersions(): void
    {
        $projects = $this->projectRepository->findProjectsForVersionsSynchronisation();

        /** @var Project $project */
        foreach ($projects as $project) {
            $this->syncReleasesForProject($project);
        }
    }

    /**
     * @param Project $project
     */
    private function syncReleasesForProject(Project $project): void
    {
        $client = $this->apiClientResolver->resolve($project->getRepository());

        $response = $client->getReleases($project);

        if (empty($response) || !is_array($response)) {
            return;
        }

        foreach ($response as $release) {
            if (!isset($release['tag_name'])) {
                continue;
            }

            $this->addReleaseForProject($project, $release);
        }
    }

    /**
     * @param Project $project
     * @param array $release
     */
    private function addReleaseForProject(Project $project, array $release): void
    {
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
