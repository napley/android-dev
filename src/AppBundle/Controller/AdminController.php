<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{

    /**
     * @Route("/admin", name="admin_homepage")
     * @Route("/admin/", name="admin_homepage")
     */
    public function indexAction()
    {
        

        return $this->render(
                        'admin/index.html.twig'
        );
    }

}
