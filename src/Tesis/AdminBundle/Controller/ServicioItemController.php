<?php

namespace Tesis\AdminBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Tesis\AdminBundle\Entity\ServicioItem;
use Tesis\AdminBundle\Form\ServicioItemType;

class ServicioItemController extends FOSRestController
{
    public function saveAction(Request $request)
    {
        $servicio = new ServicioItem();
        $form = $this->createForm(new ServicioItemType(), $servicio);
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
