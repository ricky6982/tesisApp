<?php

namespace Tesis\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MapaController extends Controller
{
    /**
     * Area de Edición del Mapa de Recorridos
     */
    public function indexAction()
    {
        return $this->render("AdminBundle:Mapa:index.html.twig", array(

            ));
    }
}