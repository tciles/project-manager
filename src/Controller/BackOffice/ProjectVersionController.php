<?php

namespace App\Controller\BackOffice;

use App\Entity\Project;
use App\Entity\ProjectVersion;
use App\Form\ProjectVersionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/projects/{id}")
 */
class ProjectVersionController extends AbstractController
{
    /**
     * @Route("/versions", name="admin_project_version_manage")
     * @ParamConverter("project", options={"mapping": {"id": "id"}})
     *
     * @param Project $project
     * @return Response
     */
    public function manage(Project $project): Response
    {
        return $this->render('backoffice/version/manage.html.twig', [
            'project' => $project
        ]);
    }

    /**
     * @Route("/versions/create", name="admin_project_version_create")
     * @ParamConverter("project", options={"mapping": {"id": "id"}})
     *
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function create(Request $request, Project $project): Response
    {
        $version = new ProjectVersion();
        $version->setProject($project);

        $form = $this->createForm(ProjectVersionType::class, $version);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $version = $form->getData();
            $this->getDoctrine()->getManager()->persist($version);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_project_update', [
                'id' => $project->getId()
            ]);
        }

        return $this->render('backoffice/version/create.html.twig', [
            'form' => $form->createView(),
            'project' => $project
        ]);
    }

    /**
     * @Route("/versions/update/{version_id}", name="admin_project_version_update")
     * @ParamConverter("version", options={"mapping": {"version_id": "id"}})
     *
     * @param Request $request
     * @param ProjectVersion $version
     * @return Response
     */
    public function update(Request $request, ProjectVersion $version): Response
    {
        $form = $this->createForm(ProjectVersionType::class, $version);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_project_update', [
                'id' => $version->getProject()->getId()
            ]);
        }

        return $this->render('backoffice/version/update.html.twig', [
            'form' => $form->createView(),
            'version' => $version
        ]);
    }
}
