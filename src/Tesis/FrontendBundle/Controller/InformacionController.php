<?php

namespace Tesis\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Tesis\AdminBundle\Entity\Servicio;
use Tesis\AdminBundle\Entity\ServicioItem;
use Tesis\FrontendBundle\Form\Filter\BusquedaType;
use Symfony\Component\HttpFoundation\Request;

class InformacionController extends Controller
{
    /**
     * Menu Principal
     *
     * @Template("FrontendBundle:Informacion:index.html.twig")
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Información del edificio
     *
     * @Template("FrontendBundle:Informacion:edificio.html.twig")
     */
    public function edificioAction()
    {
        $em = $this->getDoctrine()->getManager();

        $info = $em->getRepository('AdminBundle:Info')->find(1);

        return array(
            'info' => $info,
        );
    }

    /**
     * Listado de Servicios
     *
     * @Template("FrontendBundle:Informacion:servicios.html.twig")
     */
    public function serviciosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $servicios = $em->getRepository('AdminBundle:Servicio')->findAll();

        return array(
            'servicios' => $servicios,
        );
    }

    /**
     * Listado de items de un servicios
     *
     * @Template("FrontendBundle:Informacion:servicio_items.html.twig")
     *
     * @ParamConverter("servicio", class="AdminBundle:Servicio")
     * @param Servicio $servicio
     * @return array
     */
    public function servicioItemsAction(Servicio $servicio)
    {
        return array(
            'servicio' => $servicio,
        );
    }

    /**
     * Detalle del item seleccionado
     *
     * @Template("FrontendBundle:Informacion:item.html.twig")
     *
     * @ParamConverter("item", class="AdminBundle:ServicioItem")
     * @param ServicioItem $item
     * @return array
     */
    public function itemDetalleAction(ServicioItem $item)
    {
        return array(
            'item' => $item,
        );
    }

    /**
     * @Template("FrontendBundle:Informacion:estoy_en.html.twig")
     *
     * @ParamConverter("item", class="AdminBundle:ServicioItem")
     * @param ServicioItem $item
     * @return array
     */
    public function estoyEnAction(ServicioItem $item)
    {
        $manager = $this->get('adminbundle.manager.maparecorrido');
        $puntosReferencia = $manager->getPuntosReferencia();

        return array(
            'item' => $item,
            'puntosReferencia' => $puntosReferencia,
        );
    }

    /**
     * Muestra las indicaciones necesarias para llegar a un servicio seleccionado
     * desde un punto de inicio seleccionado por el usuario.
     *
     * @Template("FrontendBundle:Informacion:indicaciones.html.twig")
     * @param ServicioItem $item
     * @param $puntoInicio
     * @return array
     */
    public function indicacionesAction(ServicioItem $item, $puntoInicio)
    {
        $manager = $this->get('adminbundle.manager.maparecorrido');

        $puntoInicio = intval($puntoInicio);
        $indicaciones = $manager->getRutaCortaAlServicio($puntoInicio, $item->getId());

        return
            array(
                'indicaciones' => $indicaciones,
                'item' => $item,
            );
    }

    /**
     * Se encarga de mostrar un campo de busqueda y un listado con los resultado de las busqueda realizada.
     *
     * @Template("FrontendBundle:Informacion:como_llegar.html.twig")
     * @param Request $request
     * @return array
     */
    public function comoLlegarAction(Request $request)
    {
        $form = $this->createForm(new BusquedaType());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $servicios = $em->getRepository('AdminBundle:ServicioItem')->busqueda($form->get('search')->getData());

            return
                array(
                    'servicios' => $servicios,
                );
        }

        return
            array(
                'form' => $form->createView(),
            );
    }

}
