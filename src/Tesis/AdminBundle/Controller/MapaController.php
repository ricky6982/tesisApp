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

    /**
     * Obteniendo el ultimo mapa guardado - Json to PHP
     */
    public function currentMapPhpAction()
    {
        $manager = $this->get('adminbundle.manager.maparecorrido');
        // $mapa = $manager->getShortestPath(2,9);
        $mapa = $manager->getCurrentMap();

        dump($mapa['mapaJson']['edges']['_data']);
        die('El camino mas corto de 2 a 9');

        return $response;
    }

    /**
     * Controlador de Prueba para la Funcionalidad de 
     * Busqueda de un servicio en el Mapa de Recorrido
     */
    public function buscarServicioAction($id)
    {
        $manager = $this->get('adminbundle.manager.maparecorrido');

        $posicionUsuario = 1;   // Por el momento la posición del usuario va a estar en el nodo del mapa de recorrido

        $indicaciones = $manager->getRutaCortaAlServicio($posicionUsuario, $id);

        dump($indicaciones);
        die('indicaciones');

    }
}