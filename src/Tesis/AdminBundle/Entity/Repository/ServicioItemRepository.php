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
}
