<?php

namespace Tesis\AdminBundle\Manager;

use Doctrine\ORM\EntityManager;
use Tesis\AdminBundle\Lib\Dijkstras\Graph;

class MapaRecorridoManager
{
    protected $entityManager;

    private $idServicio;    // Id del Servicio que se busca.

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
     * Devuelve el nodo con información referencial. 
     */
    private function getNodo($idNodo)
    {
        $mapa = $this->getCurrentMap();
        $nodes = $mapa['mapaJson']['nodes']['_data'];
        foreach ($nodes as $node) {
            if ($node['id'] == $idNodo) {
                return $node;
                break;
            }
        }
        return null;
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
                    return array('distancia' => $min, 'direccion' => 'izq');
                }else{
                    return array('distancia' => $min, 'direccion' => 'der');
                }
            case 'to':
                $max = max($distanciasMerge);
                if (in_array($max, $distancias['der'])) {
                    return array('distancia' => $arco['distancia'] - $max, 'direccion' => 'der');
                }else{
                    return array('distancia' => $arco['distancia'] - $max, 'direccion' => 'izq');
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
        $indicaciones = array(array(
            'nodo' => $secuenciaNodos[0],
            'infRef' => array(),
            'distancia' => '',
            'direccion' => ''
        ));

        for ($i=0; $i < count($secuenciaNodos) - 1; $i++) {
            $arco = $this->getArco($secuenciaNodos[$i], $secuenciaNodos[$i+1]);
            $distancia = (isset($arco['distancia'])) ? $arco['distancia'] : null;
            $infRef = (isset($arco['infRef']) ? $arco['infRef'] : '' );
            $direccion = $this->getDireccionEntreNodosAdyacentes($secuenciaNodos[$i], $secuenciaNodos[$i+1]);
            array_push($indicaciones, array(
                'nodo' => $secuenciaNodos[$i+1],
                'infRef' => array($infRef),
                'distancia' => $distancia,
                'direccion' => $direccion
            ));
        }

        $indicacionesMin = array();
        $i = 0;
        while ($i < count($indicaciones)) {
            $j = $i + 1;
            $agrupar = false;
            while ($j < count($indicaciones) and $indicaciones[$i]['direccion'] == $indicaciones[$j]['direccion']){
                $agrupar = true;
                $j += 1;
            }
            if ($agrupar) {
                $agrupacion = array('nodo' => null, 'infRef' => array(), 'distancia' => 0, 'direccion' => $indicaciones[$i]['direccion']);
                for ($k = $i; $k < $j ; $k++) { 
                    $agrupacion = array(
                            'nodo' => $indicaciones[$k]['nodo'],
                            'infRef' => array_unique(array_merge($agrupacion['infRef'], $indicaciones[$k]['infRef'])),
                            'distancia' => $agrupacion['distancia'] + $indicaciones[$k]['distancia'],
                            'direccion' => $indicaciones[$k]['direccion']
                        );
                }
                array_push($indicacionesMin, $agrupacion);
            }else{
                array_push($indicacionesMin, $indicaciones[$i]);
            }
            $i = $j;
        }

        return $indicacionesMin;
    }

    /**
     * Determina hacia donde debe el usuario girar a su derecha o a su izquierda.
     */
    private function getRotacion($inicio, $fin)
    {
        $giroDerecha = " izq arr der abj izq ";
        $giroIzquierda = " der arr izq abj der ";

        $direccion = $inicio." ".$fin;
        if (strpos($giroDerecha, $direccion)) {
            return "derecha";
        }elseif (strpos($giroIzquierda, $direccion)) {
            return "izquierda";
        }else{
            return null;
        }
    }

    /**
     * Devuelve un array con indicaciones relativas a la posicion y direccion del usuario
     * 
     * $secuenciaNodos: array con la secuencia de nodos desde el origen hasta el nodo final cercano al servicio.
     */
    private function getIndicaciones($secuenciaNodos)
    {
        $vectorIndicaciones = $this->getArrayIndicaciones($secuenciaNodos);
        $guia = array();
        // Crea las indicaciones desde el nodo inicial hasta el nodo final
        for ($i=0; $i < count($vectorIndicaciones) - 1; $i++) { 
            if ($i == 0) {
                $lugares = implode(' y ', $vectorIndicaciones[$i+1]['infRef']);
                $step = sprintf("Camine %s metros hacia adelante, por %s", $vectorIndicaciones[$i+1]['distancia'], $lugares);
            }else{
                $giro = $this->getRotacion($vectorIndicaciones[$i]['direccion'], $vectorIndicaciones[$i+1]['direccion']);
                $lugares = implode(',', $vectorIndicaciones[$i+1]['infRef']);
                $step = sprintf("Gire a su %s y camine %s metros en esa dirección, Ud recorrera %s", $giro, $vectorIndicaciones[$i+1]['distancia'], $lugares);
            }
            array_push($guia, $step);
        }

        $ultimaDireccion = $vectorIndicaciones[count($vectorIndicaciones)-1]['direccion'];

        return $guia;
    }

    /**
     * Devuelve una cadena con la indicación desde el ultimo nodo hacia el servicio.
     * $direccion: ultima dirección hasta el nodo final de la ruta
     * $nodo: ultimo nodo desde el cual se llega al servicio
     * $arco: arco con la información referencial para determinar que direccion debe tomar el usuario
     * $idServicio: servicio al cual el usuario debe llegar.
     */
    private function getIndicacionFinal($direccion, $nodo, $arco, $idServicio)
    {
        if ($arco['from'] == $nodo) {
            $distancia = $this->getDistanciaAlServicio('from', $idServicio, $arco);
            $giro = $this->getDireccionEntreNodosAdyacentes($nodo, $arco['to']);
        }else{
            $distancia = $this->getDistanciaAlServicio('to', $idServicio, $arco);
            $giro = $this->getDireccionEntreNodosAdyacentes($nodo, $arco['from']);
        }

        $posicionRespectoArco = $distancia['direccion'] == 'der' ? 'derecha' : 'izquierda';

        if ($direccion == $giro) {
            $instruccion = sprintf("Siga hacia adelante %s metros y a su mano %s esta el servicio", $distancia['distancia'], $posicionRespectoArco);
        }else{
            $rotacion = $this->getRotacion($direccion, $distancia['direccion']);
            $rotacion = $rotacion == 'der' ? 'derecha' : 'izquierda';
            $instruccion = sprintf("Gire a su %s, camine %s metros y a su mano %s se encuentra el servicio que ud solicito.", $rotacion, $distancia['distancia'], $posicionRespectoArco);
        }

        return $instruccion;
    }

    /**
     * Obtiene las rutas desde la posicionActual del usuario hasta cada uno de los nodos que conforman
     * los arcos en los que se encuentra el servicio.
     *
     *  Devuelve un Array con las instrucciones que el usuario debe seguir para llegar a su destino.
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
            $direccionFinal = $this->getArrayIndicaciones($path);
            $direccionFinal = array_pop($direccionFinal)['direccion'];
            $instruccionFinal = $this->getIndicacionFinal(
                                    $direccionFinal,
                                    $nodosCercanos[array_keys($distancias, min($distancias))[0]],
                                    $arcos[array_keys($distancias, min($distancias))[0]],
                                    $servicio
                                );
            $instrucciones = $this->getIndicaciones($path);
            array_push($instrucciones, $instruccionFinal);
        }

        return $instrucciones;
    }

    /**
     * Obtener los puntos de referencias que estan establecidos en el Mapa de Recorrido
     */
    public function getPuntosReferencia()
    {
        $mapa = $this->getCurrentMap();

        $nodos = $mapa["mapaJson"]["nodes"]["_data"];

        $puntosReferencia = array();
        foreach ($nodos as $nodo) {
            if (isset($nodo["tipo"]) and $nodo["tipo"] == 0) {
                array_push($puntosReferencia, $nodo);
            }
        }

        return $puntosReferencia;
    }
}
