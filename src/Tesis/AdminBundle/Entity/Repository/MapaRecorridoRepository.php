<?php

namespace Tesis\AdminBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class MapaRecorridoRepository extends EntityRepository
{
    /**
     * Busca la ultima version guardada del mapa de recorrido
     * para enviarla en formato JSON.
     */
    public function findCurrentMap()
    {
        $em = $this->getEntityManager();

        $consultaDql = "SELECT m
                        FROM AdminBundle:MapaRecorrido m
                        ORDER BY m.fechaModificacion DESC
        ";

        $consulta = $em->createQuery($consultaDql);

        return $consulta->setMaxResults(1)->getArrayResult();
    }
}