<?php

namespace App\Controller\BackOffice;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/projects")
 */
class ProjectController extends AbstractController
{
    /**
     * @Route("/", name="admin_project_manage")
     * @param ProjectRepository $repository
     * @return Response
     */
    public function manage(ProjectRepository $repository): Response
    {
        $projects = $repository->findAll();

        return $this->render('backoffice/project/manage.html.twig', [
            'projects' => $projects
        ]);
    }

    /**
     * @Route("/create", name="admin_project_create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
            $this->getDoctrine()->getManager()->persist($project);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_project_manage');
        }

        return $this->render('backoffice/project/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/update/{id}", name="admin_project_update")
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function update(Request $request, Project $project): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_project_manage');
        }

        return $this->render('backoffice/project/update.html.twig', [
            'form' => $form->createView(),
            'project' => $project
        ]);
    }
}
