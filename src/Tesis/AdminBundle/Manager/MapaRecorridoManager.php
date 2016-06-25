<?php

namespace Tesis\AdminBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Tesis\AdminBundle\Lib\Dijkstras\Graph;

class MapaRecorridoManager
{
    protected $entityManager;

    private $idServicio;    // Id del Servicio que se busca.

    public function setEntityManager(ObjectManager $entityManager)
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
                $totalConexiones[$arco['from']][$arco['to']] = isset($arco['distancia']) ? $arco['distancia'] : 1000;
            } else {
                $totalConexiones[$arco['from']] = array($arco['to'] => (isset($arco['distancia']) ? $arco['distancia'] : 1000));
            };
        }
        foreach ($arcos as $arco) {
            if (isset($totalConexiones[$arco['to']])) {
                $totalConexiones[$arco['to']][$arco['from']] = isset($arco['distancia']) ? $arco['distancia'] : 1000;
            } else {
                $totalConexiones[$arco['to']] = array($arco['from'] => (isset($arco['distancia']) ? $arco['distancia'] : 1000));
            };
        }

        foreach ($totalConexiones as $nodo => $conexiones) {
            $graph->add_vertex($nodo, $conexiones);
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
     * @param integer $id del servicio a buscar
     * @return array Arcos
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

        return $arcos;
    }

    /**
     * @param $nodoA integer
     * @param $nodoB integer
     * @return array edge|emptyArray
     */
    public function getArco($nodoA, $nodoB)
    {
        $mapa = $this->getCurrentMap();
        $edges = $mapa['mapaJson']['edges']['_data'];
        foreach ($edges as $edge) {
            if (($edge['from'] == $nodoA and $edge['to'] == $nodoB) or ($edge['from'] == $nodoB and $edge['to'] == $nodoA)) {
                return $edge;
                break;
            }
        }

        return array();
    }

    /**
     * Devuelve el nodo con información referencial.
     * @param $idNodo
     * @return null
     */
    public function getNodo($idNodo)
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
     * @param $nodoA integer
     * @param $nodoB integer
     * @return mixed|null
     */
    public function getDistanciaEntreNodosAdyacentes($nodoA, $nodoB)
    {
//        TODO: Deberia lanzar excepcion si los nodos no son adyacentes.
        $arco = $this->getArco($nodoA, $nodoB);

        if (isset($arco['distancia'])) {
            return $arco['distancia'];
        }

        return null;
    }

    /**
     * Devuelve la dirección que existe entre dos nodos adyacentes
     * (izq|der|arr|abj) según donde se encuentra el nodoB respecto el nodoA
     * @param $nodoA integer
     * @param $nodoB integer
     * @return null
     */
    public function getDireccionEntreNodosAdyacentes($nodoA, $nodoB)
    {
//        TODO: Deberia lanzar excepcion si los nodos no son adyacentes.
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
     * @param array $secuenciaNodos
     * @return int
     */
    public function getDistanciaTotalEntreNodos($secuenciaNodos)
    {
//        TODO: deberia lanzar excepción o warning si la secuencia es invalida.
        $distanciaTotal = 0;
        for ($i = 0; $i < count($secuenciaNodos) - 1; $i++) {
            $distanciaTotal = $distanciaTotal + $this->getDistanciaEntreNodosAdyacentes(
                    $secuenciaNodos[$i],
                    $secuenciaNodos[$i + 1]
                );
        }

        return $distanciaTotal;
    }

    /**
     * Devuelve la distancia que existe entre el "nodo de un arco" hasta
     * el "servicio" localizado en el mismo arco
     *
     * @param string $tipoNodo Especifica si es el nodo From o el nodo To del arco
     * @param integer $idServicio del servicio que se encuentra en el arco
     * @param array $arco que contiene toda la información
     * @return array
     */
    public function getDistanciaAlServicio($tipoNodo, $idServicio, $arco)
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

        switch ($tipoNodo) {
            case 'from':
                $min = min($distanciasMerge);
                if (in_array($min, $distancias['der'])) {
                    return array('distancia' => $min, 'direccion' => 'der');
                } else {
                    return array('distancia' => $min, 'direccion' => 'izq');
                }
            case 'to':
                $max = max($distanciasMerge);
                if (in_array($max, $distancias['der'])) {
                    return array('distancia' => $arco['distancia'] - $max, 'direccion' => 'izq');
                } else {
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
     * @param array $secuenciaNodos
     * @return array
     */
    public function getArrayIndicaciones($secuenciaNodos)
    {
        $indicaciones = array(
            array(
                'nodo' => $secuenciaNodos[0],
                'infRef' => array(),
                'distancia' => '',
                'direccion' => '',
            ),
        );

        for ($i = 0; $i < count($secuenciaNodos) - 1; $i++) {
            $arco = $this->getArco($secuenciaNodos[$i], $secuenciaNodos[$i + 1]);
            $distancia = (isset($arco['distancia'])) ? $arco['distancia'] : null;
            $infRef = (isset($arco['infRef']) ? $arco['infRef'] : '');
            $direccion = $this->getDireccionEntreNodosAdyacentes($secuenciaNodos[$i], $secuenciaNodos[$i + 1]);
            array_push(
                $indicaciones,
                array(
                    'nodo' => $secuenciaNodos[$i + 1],
                    'infRef' => array($infRef),
                    'distancia' => $distancia,
                    'direccion' => $direccion,
                )
            );
        }

        $indicacionesMin = array();
        $i = 0;
        while ($i < count($indicaciones)) {
            $j = $i + 1;
            $agrupar = false;
            while ($j < count($indicaciones) and $indicaciones[$i]['direccion'] == $indicaciones[$j]['direccion']) {
                $agrupar = true;
                $j += 1;
            }
            if ($agrupar) {
                $agrupacion = array(
                    'nodo' => null,
                    'infRef' => array(),
                    'distancia' => 0,
                    'direccion' => $indicaciones[$i]['direccion'],
                );
                for ($k = $i; $k < $j; $k++) {
                    $agrupacion = array(
                        'nodo' => $indicaciones[$k]['nodo'],
                        'infRef' => array_unique(array_merge($agrupacion['infRef'], $indicaciones[$k]['infRef'])),
                        'distancia' => $agrupacion['distancia'] + $indicaciones[$k]['distancia'],
                        'direccion' => $indicaciones[$k]['direccion'],
                    );
                }
                array_push($indicacionesMin, $agrupacion);
            } else {
                array_push($indicacionesMin, $indicaciones[$i]);
            }
            $i = $j;
        }

        return $indicacionesMin;
    }

    /**
     * Determina hacia donde debe el usuario girar a su derecha o a su izquierda.
     * @param string $inicio
     * @param string $fin
     * @return null|string
     */
    private function getRotacion($inicio, $fin)
    {
        $giroDerecha = " izq arr der abj izq ";
        $giroIzquierda = " der arr izq abj der ";

//      TODO: Deberia lanzar excepción si $inicio o $fin son cadenas invalidas

        $direccion = $inicio." ".$fin;
        if (strpos($giroDerecha, $direccion)) {
            return "derecha";
        } elseif (strpos($giroIzquierda, $direccion)) {
            return "izquierda";
        } else {
            return null;
        }
    }

    /**
     * Obtiene las rutas desde la posicionActual del usuario hasta cada uno de los nodos que conforman
     * los arcos en los que se encuentra el servicio.
     *
     *  Devuelve un Array con las instrucciones que el usuario debe seguir para llegar a su destino.
     * @param integer $posicionActual nodo inicial, donde la persona se encuentra.
     * @param integer $servicio id del servicio
     * @return array
     */
    public function getRutaCortaAlServicio($posicionActual, $servicio)
    {
        $arcos = $this->getUbicacionServicio($servicio);
        $instrucciones = array();
        $distancias = array();
        $nodosCercanos = array();
        $nodosLejanos = array();

        foreach ($arcos as $arco) {
            $distanciaNodoFrom = $this->getDistanciaTotalEntreNodos(
                $this->getShortestPath($posicionActual, $arco['from'])
            );
            $distanciaNodoTo = $this->getDistanciaTotalEntreNodos($this->getShortestPath($posicionActual, $arco['to']));
            $distanciaAlServicioPorNodoFrom = $distanciaNodoFrom + $this->getDistanciaAlServicio(
                    'from',
                    $servicio,
                    $arco
                )['distancia'];
            $distanciaAlServicioPorNodoTo = $distanciaNodoTo + $this->getDistanciaAlServicio(
                    'to',
                    $servicio,
                    $arco
                )['distancia'];

            if ($distanciaAlServicioPorNodoFrom < $distanciaAlServicioPorNodoTo) {
                $distanciaMin = $distanciaAlServicioPorNodoFrom;
                $nodoCercano = $arco['from'];
                $nodoLejano = $arco['to'];
            } else {
                $distanciaMin = $distanciaAlServicioPorNodoTo;
                $nodoCercano = $arco['to'];
                $nodoLejano = $arco['from'];
            }

            array_push($distancias, $distanciaMin);
            array_push($nodosCercanos, $nodoCercano);
            array_push($nodosLejanos, $nodoLejano);
        }

        if (count($distancias) > 0) {
            $indexDistanciaMin = array_keys($distancias, min($distancias))[0];
            $path = $this->getShortestPath($posicionActual, $nodosLejanos[$indexDistanciaMin]);
            $arrayIndicaciones = $this->getArrayIndicaciones($path);
            $ultimoNodo = $path[count($path) - 1];
            $penultimoNodo = $path[count($path) - 2];
            $ultimoArco = $this->getArco($penultimoNodo, $ultimoNodo);
            if ($penultimoNodo == $ultimoArco['from']) {
                $distanciaAlServicio = $this->getDistanciaAlServicio('from', $servicio, $ultimoArco);
            } else {
                $distanciaAlServicio = $this->getDistanciaAlServicio('to', $servicio, $ultimoArco);
            }

            $ultimaDistancia = $arrayIndicaciones[count($arrayIndicaciones) - 1]['distancia']
                - $ultimoArco['distancia'] + $distanciaAlServicio['distancia'];
            $arrayIndicaciones[count($arrayIndicaciones) - 1]['distancia'] = $ultimaDistancia;

            $instrucciones = $this->getIndicaciones($arrayIndicaciones, $distanciaAlServicio['direccion']);
        }

        return $instrucciones;
    }

    /**
     * Devuelve un array con indicaciones relativas a la posicion y direccion del usuario
     *
     * @param array $arrayIndicaciones secuencia de indicaciones hasta el nodo final
     * @param string $ubicacionRelativaServicio 'izq'|'der' representa a que lado se encuentra el servicio
     * @return array
     */
    public function getIndicaciones($arrayIndicaciones, $ubicacionRelativaServicio)
    {
        $guia = array();
        array_shift($arrayIndicaciones);
        for ($i = 0; $i < count($arrayIndicaciones); $i++) {
            $caminar = sprintf(
                "Camine %s metros por %s",
                $arrayIndicaciones[$i]['distancia'],
                implode(", ", $arrayIndicaciones[$i]['infRef'])
            );
            array_push($guia, $caminar);
            if ($i == count($arrayIndicaciones) - 1) {
                $ubicacion = $ubicacionRelativaServicio == "izq" ? "izquierda" : "derecha";
                $llegar = sprintf("A su mano %s se encuentra el servicio que solicito.", $ubicacion);
                array_push($guia, $llegar);
            } else {
                $giro = $this->getRotacion(
                    $arrayIndicaciones[$i]['direccion'],
                    $arrayIndicaciones[$i + 1]['direccion']
                );
                $girar = sprintf("Gire a su %s", $giro);
                array_push($guia, $girar);
            }
        }

        return $guia;
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
            if (isset($nodo["tipo"]) and intval($nodo["tipo"]) == 0) {
                array_push($puntosReferencia, $nodo);
            }
        }

        return $puntosReferencia;
    }

    /**
     * Dado una secuencia de nodos consecutivos valido debe devolver cada uno de los arcos que los conectan.
     * @param $secuenciaNodos array
     * @return array $arcos
     */
    public function getSecuenciaDeArcos($secuenciaNodos)
    {
        $arcos = array();
        for ($i = 0; $i < count($secuenciaNodos) - 1; $i++) {
            $arco = $this->getArco($secuenciaNodos[$i], $secuenciaNodos[$i + 1]);
            array_push($arcos, $arco);
        }

        return $arcos;
    }
}
