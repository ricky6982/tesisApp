<?php

namespace Tesis\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tesis\AdminBundle\Form\ServicioType;
use Tesis\AdminBundle\Form\ServicioItemType;

class UbicacionController extends Controller
{
    public function indexAction()
    {
        $servForm = $this->createForm(new ServicioType());
        $servItemForm = $this->createForm(new ServicioItemType());
        $em = $this->getDoctrine()->getManager();
        $servicios = $em->getRepository('AdminBundle:Servicio')->findAll();

        // return $this->render('AdminBundle:Ubicacion:index.html.twig', array(
        //         'servForm' => $servForm->createView(),
        //         'servItemForm' => $servItemForm->createView(),
        //         'servicios' => $servicios,
        //     ));

        // Implementación RESTfull
        return $this->render('AdminBundle:Ubicacion:indexRestfull.html.twig', array(
                'servForm' => $servForm->createView(),
                'servItemForm' => $servItemForm->createView(),
            ));
    }

}
