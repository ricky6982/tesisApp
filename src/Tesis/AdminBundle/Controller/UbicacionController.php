<?php

namespace Tesis\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UbicacionController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Ubicacion:index.html.twig', array(
                // ...
            ));    }

}
