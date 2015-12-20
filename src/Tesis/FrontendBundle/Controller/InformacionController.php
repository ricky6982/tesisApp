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
     * @Template("FrontendBundle:Informacion:index.html.twig")
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Template("FrontendBundle:Informacion:edificio.html.twig")
     */
    public function edificioAction()
    {
        $em = $this->getDoctrine()->getManager();

        $info = $em->getRepository('AdminBundle:Info')->find(1);

        return array(
                'info' => $info
            );
    }

    /**
     * @Template("FrontendBundle:Informacion:servicios.html.twig")
     */
    public function serviciosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $servicios = $em->getRepository('AdminBundle:Servicio')->findAll();
        
        return array(
                'servicios' => $servicios
            );
    }

    /**
     * @Template("FrontendBundle:Informacion:servicio_detalle.html.twig")
     * @ParamConverter("servicio", class="AdminBundle:Servicio")
     */
    public function servicioDetalleAction(Servicio $servicio)
    {
        return array(
                'servicio' => $servicio
            );
    }

    /**
     * @Template("FrontendBundle:Informacion:item.html.twig")
     * @ParamConverter("item", class="AdminBundle:ServicioItem")
     */
    public function servicioItemAction(ServicioItem $item)
    {
        return array(
                'item' => $item
            );
    }

    /**
     * Se encarga de mostrar un campo de busqueda y un listado con los resultado de las busqueda realizada.
     */
    public function comoLlegarAction(Request $request)
    {
        $form = $this->createForm(new BusquedaType());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $servicios = $em->getRepository('AdminBundle:ServicioItem')->busqueda($form->get('search')->getData());
            
            return $this->render('FrontendBundle:Informacion:resultado_busqueda.html.twig', array(
                    'servicios' => $servicios
                ));
        }

        return $this->render("FrontendBundle:Informacion:como_llegar.html.twig", array(
                'form' => $form->createView()
            ));
    }

}
