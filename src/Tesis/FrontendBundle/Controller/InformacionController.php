<?php

namespace Tesis\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Tesis\AdminBundle\Entity\Servicio;

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

    // public function comoLlegarAction()
    // {
    //     return array(
    //             // ...
    //         );
    // }

    // public function buscarAction()
    // {
    //     return array(
    //             // ...
    //         );
    // }

}
