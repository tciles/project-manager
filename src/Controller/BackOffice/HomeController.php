<?php

namespace App\Controller\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_home")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="admin_home_index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('backoffice/base.html.twig');
    }
}
