<?php

namespace App\Controller\FrontOffice;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Repository\ProjectVersionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_index")
     * @param ProjectRepository $repository
     * @return Response
     */
    public function listProjects(ProjectRepository $repository): Response
    {
        $projects = $repository->findBy(['active' => 1]);

        return $this->render('frontoffice/list_projects.html.twig', [
            'projects' => $projects,
        ]);
    }

    /**
     * @Route("/projects/{id}", name="project_show")
     * @param Project $project
     * @param ProjectVersionRepository $projectVersionRepository
     * @return Response
     */
    public function showProject(Project $project, ProjectVersionRepository $projectVersionRepository): Response
    {
        $versions = $projectVersionRepository->findBy([
            'active' => true,
            'project' => $project->getId()
        ], [
            'id' => 'DESC'
        ]);

        $project->setVersions(new ArrayCollection($versions));

        return $this->render('frontoffice/show_project.html.twig', [
            'project' => $project,
        ]);
    }
}
