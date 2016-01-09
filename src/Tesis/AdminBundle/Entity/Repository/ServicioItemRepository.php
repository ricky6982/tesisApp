<?php

namespace Tesis\AdminBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class ServicioItemRepository extends EntityRepository
{
    public function busqueda($word){
        $em = $this->getEntityManager();

        $consultaDql = 'SELECT s
                        FROM AdminBundle:ServicioItem s
                        WHERE s.nombre like :word OR s.descripcion like :word
        ';

        $consulta = $em->createQuery($consultaDql);
        $consulta->setParameter('word', '%'.$word.'%');

        return $consulta->getResult();
    }

    public function findItemFromService($idServicio, $idItem)
    {
        $em = $this->getEntityManager();

        $consultaDql = 'SELECT i
                        FROM AdminBundle:ServicioItem i
                        LEFT JOIN i.servicio s
                        WHERE i.id = :idItem and s.id = :idServicio
                        ';
        $consulta = $em->createQuery($consultaDql);
        $consulta->setParameters(array(
                'idItem' => $idItem,
                'idServicio' => $idServicio
            ));

        return $consulta->getArrayResult();
    }
}
