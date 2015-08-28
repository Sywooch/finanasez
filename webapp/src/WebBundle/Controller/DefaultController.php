<?php

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/help", name="help")
     * @Template
     */
    public function helpAction()
    {
        return [];
    }

    /**
     * @Route("/signup", name="signup")
     * @Template()
     */
    public function signupAction()
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('index'));
        }

        return [];
    }


    /**
     * @Route("/bills", name="bills")
     * @Template()
     */
    public function billsAction()
    {
        return [];
    }


    /**
     * @Route("/categories", name="categories")
     * @Template()
     */
    public function categoriesAction()
    {
        return [];
    }

    /**
     * @Route("/stats", name="stats")
     * @Template()
     */
    public function statsAction()
    {
        return [];
    }
}
