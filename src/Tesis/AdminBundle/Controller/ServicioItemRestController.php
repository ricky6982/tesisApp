<?php

namespace Tesis\AdminBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Tesis\AdminBundle\Entity\Servicio;
use Tesis\AdminBundle\Entity\ServicioItem;
use Tesis\AdminBundle\Form\ServicioItemType;

class ServicioItemRestController extends FOSRestController
{
    /**
     * @ParamConverter("servicio", class="AdminBundle:Servicio")
     */
    public function getServicioItemsAction(Servicio $servicio)
    {
        $view = $this->view($servicio->getItems());

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * @ParamConverter("servicioItem", class="AdminBundle:ServicioItem", options={"id" = "servicioItem"})
     */
    public function getServicioItemAction($id, ServicioItem $servicioItem)
    {
        $manager = $this->get('adminbundle.manager.servicio_item');
        $item = $manager->findItemFromService($id, $servicioItem->getId());
        $view = $this->view($item);

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Actualización de datos en ServicioItem
     */
    public function putServicioItemAction($id, $servicioItem, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AdminBundle:ServicioItem')->find($servicioItem);
        $form = $this->getForm($item, array('method' => 'PUT'));
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->flush();
            $view = $this->view($item);
        }else{
            $view = $this->view($form, 500);
        }


        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Creación de un nuevo ServicioItem
     */
    public function postServicioItemAction($id, Request $request)
    {
        $servicioItem = new ServicioItem();
        $form = $this->getForm($servicioItem);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($servicioItem);
            $em->flush();
        }

        $view = $this->view($servicioItem);

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * @ParamConverter("servicioItem", class="AdminBundle:ServicioItem")
     */
    public function deleteServicioItemAction($id, ServicioItem $servicioItem)
    {
        if ($servicioItem->getServicio()->getId() == $id) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($servicioItem);
            $em->flush();
            $view = $this->view(null, 200);
        }else{
            $view = $this->view(null, 500);
        }

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Creación del Formulario
     */
    private function getForm($servicioItem = null, $options = array())
    {
        if (empty($options)) {
            return $this->createForm(new ServicioItemType(), $servicioItem);
        }else{
            return $this->createForm(new ServicioItemType(), $servicioItem, $options);
            
        }
    }
}