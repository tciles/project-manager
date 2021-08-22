<?php

namespace App\Controller\BackOffice;

use App\Entity\ProjectTag;
use App\Form\ProjectTagType;
use App\Repository\ProjectTagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tags")
 */
class ProjectTagController extends AbstractController
{
    /**
     * @Route("/", name="admin_tag_manage")
     * @param ProjectTagRepository $repository
     * @return Response
     */
    public function manage(ProjectTagRepository $repository): Response
    {
        $tags = $repository->findAll();

        return $this->render('backoffice/project_tag/manage.html.twig', [
            'tags' => $tags
        ]);
    }

    /**
     * @Route("/create", name="admin_tag_create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $tag = new ProjectTag();
        $form = $this->createForm(ProjectTagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tag = $form->getData();
            $this->getDoctrine()->getManager()->persist($tag);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_tag_manage');
        }

        return $this->render('backoffice/project_tag/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/update/{id}", name="admin_tag_update")
     * @param Request $request
     * @param ProjectTag $tag
     * @return Response
     */
    public function update(Request $request, ProjectTag $tag): Response
    {
        $form = $this->createForm(ProjectTagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_tag_update', [
                'id' => $tag->getId()
            ]);
        }

        return $this->render('backoffice/project_tag/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
