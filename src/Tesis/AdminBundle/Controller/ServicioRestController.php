<?php

namespace Tesis\AdminBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Tesis\AdminBundle\Entity\Servicio;
use Tesis\AdminBundle\Form\ServicioType;

class ServicioRestController extends FOSRestController
{
    public function getServiciosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('AdminBundle:Servicio')->findAll();
        $view = $this->view($data, 200)
            ->setFormat('json');

        return $this->handleView($view);
    }

    public function getServicioAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $servicio = $em->getRepository('AdminBundle:Servicio')->find($id);
        $view = $this->view($servicio, 200);

        return $this->handleView($view);
    }

    public function postServicioAction(Request $request)
    {
        $servicio = new Servicio();
        $form = $this->getForm($servicio);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($servicio);
            $em->flush();
        }

        $view = $this->view($form);

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    protected function getForm($servicio = null)
    {
        return $this->createForm(new ServicioType(), $servicio);
    }

    /**
     * @ParamConverter("servicio", class="AdminBundle:Servicio")
     */
    public function deleteServicioAction(Servicio $servicio)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($servicio);
        $em->flush();

        $view = $this->view();

        return $this->get('fos_rest.view_handler')->handle($view);
    }
}