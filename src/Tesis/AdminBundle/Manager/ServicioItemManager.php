<?php

namespace Tesis\AdminBundle\Manager;

class ServicioItemManager extends BaseManager
{
    public function getRepositoryName()
    {
        return 'AdminBundle:ServicioItem';
    }

    public function findItemFromService($idServicio, $idItem)
    {
        return $this->getRepository()->findItemFromService($idServicio, $idItem);
    }
}