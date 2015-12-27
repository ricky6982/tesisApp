<?php

namespace Tesis\AdminBundle\Manager;

use Doctrine\ORM\EntityManager;
use Tesis\AdminBundle\Lib\Dijkstras\Graph;

class MapaRecorridoManager
{
    protected $entityManager;

    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function getRepositoryName()
    {
        return 'AdminBundle:MapaRecorrido';
    }

    public function getRepository()
    {
        return $this->getEntityManager()->getRepository($this->getRepositoryName());
    }

    public function getCurrentMap()
    {
        return $this->getRepository()->findCurrentMap()[0];
    }

    public function loadDataDijkstras($map)
    {
        $graph = new Graph();
        $arcos = $map['mapaJson']['edges']['_data'];

        // Definiendo todas las conexiones que tiene cada nodo
        $totalConexiones = array();
        foreach ($arcos as $arco) {
            if (isset($totalConexiones[$arco['from']])) {
                $totalConexiones[$arco['from']][$arco['to']] = isset($arco['distancia'])? $arco['distancia'] : 1000;
            }else{
                $totalConexiones[$arco['from']] = array($arco['to'] => (isset($arco['distancia'])?$arco['distancia']:1000));
            };
        }
        foreach ($arcos as $arco) {
            if (isset($totalConexiones[$arco['to']])) {
                $totalConexiones[$arco['to']][$arco['from']] = isset($arco['distancia'])? $arco['distancia'] : 1000;
            }else{
                $totalConexiones[$arco['to']] = array($arco['from'] => (isset($arco['distancia'])?$arco['distancia']:1000));
            };
        }

        foreach ($totalConexiones as $nodo => $conexiones) {
            $graph->add_vertex( $nodo, $conexiones );
        }

        return $graph;
    }

    public function getShortestPath($inicio, $fin)
    {
        $graph = $this->loadDataDijkstras($this->getCurrentMap());

        return $graph->shortest_path($inicio, $fin);
    }

    /**
     * Devuelve un array de Arcos (Edges) en los cuales se encuentra el id de servicio
     */
    public function getUbicacionServicio($id)
    {
        $mapa = $this->getCurrentMap();

        $arcos = array();
        foreach ($mapa['mapaJson']['edges']['_data'] as $arco) {
            if (isset($arco['lugares'])) {
                if (count($arco['lugares']['izq']) > 0) {
                    foreach ($arco['lugares']['izq'] as $servicio) {
                        if ($servicio['idServicio'] == $id) {
                            array_push($arcos, $arco);
                        }
                    }
                }
                if (count($arco['lugares']['der']) > 0) {
                    foreach ($arco['lugares']['der'] as $servicio) {
                        if ($servicio['idServicio'] == $id) {
                            array_push($arcos, $arco);
                        }
                    }
                }
            }
        }
        // dump($arcos);
        // die('arcos encontrados');

        return $arcos;  // Devuelve un array con los arcos donde el servicio fue encontrado.
    }

    public function getArco($nodoA, $nodoB)
    {
        $mapa = $this->getCurrentMap();
        $edges = $mapa['mapaJson']['edges']['_data'];
        foreach ($edges as $edge) {
            if (($edge['from'] == $nodoA and $edge['to'] == $nodoB) or ($edge['from'] == $nodoB and $edge['to'] == $nodoA )) {
                return $edge;
                break;
            }
        }
        return array();
    }

    /**
     * Devuelve la distancia entre los nodos en caso de que exista un arco que los une, de otro modo devuelve null.
     */
    public function getDistanciaEntreNodosAdyacentes($nodoA, $nodoB)
    {
        $arco = $this->getArco($nodoA, $nodoB);

        if (isset($arco['distancia'])) {
            return $arco['distancia'];
        }

        return null;
    }

    /**
     * Devuelve la dirección que existe entre dos nodos adyacentes
     */
    private function getDireccionEntreNodosAdyacentes($nodoA, $nodoB)
    {
        $mapa = $this->getCurrentMap();
        $nodes = $mapa['mapaJson']['nodes']['_data'];

        foreach ($nodes as $nodo) {
            if ($nodo['id'] == $nodoA) {
                if (isset($nodo['conexiones'])) {
                    if (isset($nodo['conexiones'][$nodoB])) {
                        return $nodo['conexiones'][$nodoB];
                        break;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Devuelve la distancia total de una secuencia de nodos adyacentes.
     */
    public function getDistanciaTotalEntreNodos($secuenciaNodos)
    {
        $distanciaTotal = 0;
        for ($i=0; $i < count($secuenciaNodos) - 1; $i++) { 
            $distanciaTotal = $distanciaTotal + $this->getDistanciaEntreNodosAdyacentes($secuenciaNodos[$i], $secuenciaNodos[$i+1]);
        }

        return $distanciaTotal;
    }

    /**
     * Devuelve la distancia que existe entre el nodo de un arco hasta el servicio localizado en el mismo arco
     *
     * $nodo: Especifica si es el nodo From o el nodo To del arco
     * $servicio: Id del servicio que se encuentra en el arco
     * $arco: arco que contiene toda la información
     */
    public function getDistanciaAlServicio($nodo, $idServicio, $arco)
    {
        $distancias = array('der' => array(), 'izq' => array());
        foreach ($arco['lugares']['der'] as $servicio) {
            if ($servicio['idServicio'] == $idServicio) {
                array_push($distancias['der'], $servicio['distancia']);
            };
        }
        foreach ($arco['lugares']['izq'] as $servicio) {
            if ($servicio['idServicio'] == $idServicio) {
                array_push($distancias['izq'], $servicio['distancia']);
            };
        }
        $distanciasMerge = array_merge($distancias['der'], $distancias['izq']);

        switch ($nodo) {
            case 'from':
                $min = min($distanciasMerge);
                if (in_array($min, $distancias['der'])) {
                    return array('distancia' => $min, 'direccion' => 'der');
                }else{
                    return array('distancia' => $min, 'direccion' => 'izq');
                }
            case 'to':
                $max = max($distanciasMerge);
                if (in_array($max, $distancias['der'])) {
                    return array('distancia' => $arco['distancia'] - $max, 'direccion' => 'izq');
                }else{
                    return array('distancia' => $arco['distancia'] - $max, 'direccion' => 'der');
                }
        }
    }

    /**
     * Devuelve un array con indicaciones absolutas:
     * 
     * Ej: ir del nodo 16 al nodo 7, secuenciaNodos = (16,9,8,7)
     *    array(
     *      array(16, infRef, 0mts, ''),
     *      array(9, infRef, 3mts, abajo),
     *      array(8, infRef, 17.5mts, izq),
     *      array(7, infRef, 11.mts, abajo),
     *    )
     */
    private function getArrayIndicaciones($secuenciaNodos)
    {
        $indicaciones = array(array($secuenciaNodos[0], '', '', ''));

        for ($i=0; $i < count($secuenciaNodos) - 1; $i++) {
            $arco = $this->getArco($secuenciaNodos[$i], $secuenciaNodos[$i+1]);
            $distancia = (isset($arco['distancia'])) ? $arco['distancia'] : null;
            $infRef = (isset($arco['infRef']) ? $arco['infRef'] : '' );
            $direccion = $this->getDireccionEntreNodosAdyacentes($secuenciaNodos[$i], $secuenciaNodos[$i+1]);
            array_push($indicaciones, array($secuenciaNodos[$i+1], $infRef, $distancia, $direccion));
        }

        return $indicaciones;
    }

    /**
     * Devuelve un array con indicaciones relativas a la posicion y direccion del usuario
     */
    private function getIndicaciones($secuenciaNodos)
    {

    }

    /**
     * Obtiene las rutas desde la posicionActual del usuario hasta cada uno de los nodos que conforman
     * los arcos en los que se encuentra el servicio.
     */
    public function getRutaCortaAlServicio($posicionActual, $servicio)
    {
        $arcos = $this->getUbicacionServicio($servicio);
        $distancias = array();
        $nodosCercanos = array();

        foreach ($arcos as $arco) {
            $distanciaNodoA = $this->getDistanciaTotalEntreNodos($this->getShortestPath($posicionActual, $arco['from']));
            $distanciaNodoB = $this->getDistanciaTotalEntreNodos($this->getShortestPath($posicionActual, $arco['to']));
            
            if ($distanciaNodoA < $distanciaNodoB) {
                $distanciaMin = $distanciaNodoA + $this->getDistanciaAlServicio('from', $servicio, $arco)['distancia'];
                $nodoCercano = $arco['from'];
            }else{
                $distanciaMin = $distanciaNodoB  + $this->getDistanciaAlServicio('to', $servicio, $arco)['distancia'];
                $nodoCercano = $arco['to'];
            }

            array_push($distancias, $distanciaMin);
            array_push($nodosCercanos, $nodoCercano);
        }

        if (count($distancias) > 0 ) {
            $path = $this->getShortestPath($posicionActual, $nodosCercanos[array_keys($distancias, min($distancias))[0]]);
            dump($this->getArrayIndicaciones($path));
        }

        dump($distancias);
        dump($nodosCercanos);
        dump($arcos);
        dump($path);
        dump(array_keys($distancias, min($distancias)));
        die('array con distancias minimas a cada arco');

    }
}
