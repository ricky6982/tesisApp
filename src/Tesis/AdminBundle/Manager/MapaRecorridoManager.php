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

    public function getShortPath($inicio, $fin)
    {
        $graph = $this->loadDataDijkstras($this->getCurrentMap());

        return $graph->shortest_path($inicio, $fin);
    }
}
