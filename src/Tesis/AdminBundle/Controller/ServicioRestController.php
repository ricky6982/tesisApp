<?php

namespace Tesis\AdminBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Tesis\AdminBundle\Entity\Servicio;
use Tesis\AdminBundle\Form\ServicioType;

class ServicioRestController extends FOSRestController
{
    /**
     * Obtener todos los servicios
     */
    public function getServiciosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('AdminBundle:Servicio')->findAll();
        $view = $this->view($data, 200)
            ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * Obtener un servicio
     */
    public function getServicioAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $servicio = $em->getRepository('AdminBundle:Servicio')->find($id);
        $view = $this->view($servicio, 200);

        return $this->handleView($view);
    }

    /**
     * Edición del Servicio
     *
     * @ParamConverter("servicio", class="AdminBundle:Servicio")
     */
    public function putServicioAction(Servicio $servicio, Request $request)
    {
        $form = $this->getForm($servicio, array('method' => 'PUT'));
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $view = $this->view($servicio);
        }else{
            $view = $this->view($form);
        }

        return $this->handleView($view);
    }

    /**
     * Creación de un Servicio
     */
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

    /**
     * Eliminación de un Servicio
     *
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

    protected function getForm($servicio = null, $options = null)
    {
        return $this->createForm(new ServicioType(), $servicio, $options);
    }
}