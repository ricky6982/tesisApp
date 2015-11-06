<?php

namespace Tesis\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Tesis\AdminBundle\Entity\MapaRecorrido;

class MapaController extends Controller
{
    /**
     * Area de Edición del Mapa de Recorridos
     */
    public function indexAction()
    {
        return $this->render("AdminBundle:Mapa:index.html.twig", array(

            ));
    }

    /**
     * Guardando Mapa de Recorrido
     */
    public function saveAction(Request $request)
    {
        $decode = json_decode($request->getContent());
        $mapa = new MapaRecorrido();
        $mapa
            ->setFechaModificacion(new \Datetime('now'))
            ->setMapaJson($decode)
        ;

        $em = $this->getDoctrine()->getManager();
        $em->persist($mapa);
        $em->flush();

        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Obteniendo el ultimo mapa guardado
     */
    public function currentMapAction()
    {
        $em = $this->getDoctrine()->getManager();
        $mapa = $em->getRepository('AdminBundle:MapaRecorrido')->findCurrentMap();

        $response = new JsonResponse();
        if ($mapa) {
            $response->setData($mapa);
            $response->setStatusCode(Response::HTTP_OK);
        }else{
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return $response;
    }
}