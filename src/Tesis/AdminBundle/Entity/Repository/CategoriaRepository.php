<?php

namespace Tesis\AdminBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class CategoriaRepository extends EntityRepository
{
    public function finAllArray()
    {
        $em = $this->getEntityManager();

        $consultaDql = 'SELECT c.id, c.nombre
                        FROM AdminBundle:Categoria c
                        ';

        $consulta = $em->createQuery($consultaDql);

        return $consulta->getArrayResult();
    }
}