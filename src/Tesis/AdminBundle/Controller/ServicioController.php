<?php

namespace Tesis\AdminBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Tesis\AdminBundle\Entity\Servicio;
use Tesis\AdminBundle\Form\ServicioType;

class ServicioController extends FOSRestController
{
    public function saveAction(Request $request)
    {
        $servicio = new Servicio();
        $form = $this->createForm(new ServicioType(), $servicio);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($servicio);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Se guardo correctamente el servicio.');

            return $this->redirectToRoute('ubicacion_homepage');
        }

        return array(
                
            );
    }

}
