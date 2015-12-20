<?php

namespace Tesis\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tesis\AdminBundle\Entity\Usuario;

class ConfiteriaController extends Controller
{
    /**
     * @Template("FrontendBundle:Confiteria:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $locales = $em->getRepository('AdminBundle:Usuario')->findAll();

        return array(
                'locales' => $locales
            );
    }
}