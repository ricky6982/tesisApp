<?php

namespace Rest\TestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class DefaultController extends FOSRestController
{
    public function indexAction()
    {
        $data = array('nombre' => 'Ricardo', 'apellido' => 'Sarapura', 'dni'=>'32453105');
        $view = $this->view($data, 200)
            ->setTemplate("TestBundle:Default:index.html.twig")
            ->setTemplateData(array('data' => $data))
        ;

        return $this->handleView($view);
    }
}
