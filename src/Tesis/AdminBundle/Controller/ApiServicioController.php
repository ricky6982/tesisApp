<?php

namespace Tesis\AdminBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

/**
 * TODO: Eliminar el servicio
 */
class ApiServicioController extends FOSRestController
{
    public function getApiServiciosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('AdminBundle:Servicio')->findAll();
        $view = $this->view($data, 200)
            ->setFormat('json');

        return $this->handleView($view);
    }
}