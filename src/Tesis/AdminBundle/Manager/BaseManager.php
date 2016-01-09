<?php

namespace Tesis\AdminBundle\Manager;

use Doctrine\ORM\EntityManager;

/**
 * Clase que permite abstraer comportamientos a todos los managers.
 *
 */
abstract class BaseManager 
{   
    /**
     * @var ContainerInterface 
     */
    protected $container;
    
    /**
     * @var ObjectManager 
     */
    protected $entityManager;
        
    /**
     * @var String 
     */
    protected $repositoryName;

    /**
     * @var Object FormModel 
     */
    protected $model;
 
    /**
     * @var Object Entity 
     */   
    protected $entity;

    /**
     * Setea una instancia del objeto EntityManager
     * @param EntityManager $entityManager 
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * getEntityManager 
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }    

    /**
     * setContainer
     * @param ContainerInterface $container 
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * getContainer
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Setea el objeto de dominio que manipulará el manager
     *
     * @param FormModel $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * getModel
     * @return FormModel
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * setEntity
     * @param classEntity $entity 
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * getEntity 
     * @return classEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * getRepository
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->getEntityManager()->getRepository($this->getRepositoryName());
    }

    /**
     * @return Translated Text
     */
    public function translate($msj, array $parameters = array()){
        return $this->getContainer()->get('translator')->trans($msj, $parameters);
    }

    /**
     * Save model in entity and persist
     */
    public function saveModel($model = null, $andFlush = true)
    {
        if(!$model){
            $model = $this->getModel();
        }
        $entity = $this->getEntity();
        //el modelo es el encargado de popular la entidad
        $model->save($entity);
        /* persistir la entidad */
        return $this->doSave($entity, $andFlush);
    }

    /**
     *  This is basic save function. Child entity can overwrite this.
     */
    public function doSave($entity, $andFlush = true) 
    {
        $this->getEntityManager()->persist($entity);
        if ($andFlush) {
            $this->getEntityManager()->flush();
        }
        $this->setEntity($entity);
        return true;
    }

    /**
     * Verifica que la entidad sea correcta.
     * @return boolean
     */
    public function isValidEntity($entity)
    {  
        $this->errors = $this->getContainer()->get('validator')->validate($entity);

        return (!count($this->errors) > 0);
    }

    /**
     * doDelete, Elimina la entidad
     * @param  classEntity $entity [description]
     * @param  boolean $andFlush, opción para decidir si realizar el flush
     * @return boolean, exito de la operación
     */
    public function doDelete($entity, $andFlush = true) 
    {
        $this->getEntityManager()->remove($entity);
        if ($andFlush) {
            $this->getEntityManager()->flush();
        }
        return true;
    }

    /**
     * Setea los atributos de una entidad con los datos que llegan desde un arreglo
     * las llaves de este arreglo tiene que tener el mismo nombre que los atributos de la entidad
     * @param Array $data
     */
    public function fromArray($entity, array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set' . \Doctrine\Common\Util\Inflector::classify($key);
            $entity->$method($value); //@NOTE si no existe debe saltar la misma excepcion de symfony para el set.
            unset($data[$key]); //@NOTE para mejorar el uso de la memoria. Se vacia el arreglo
        }
    }
    
    public function findAllWithFilterAndOrderQuery($filters = array(), $order = array())
    {
        return $this->getRepository()
                    ->queryFiltered($filters, $order)
                    ;
    }
    
    public function findAllWithFilterAndOrder($filters = array(), $order = array())
    {
        return $this->findAllWithFilterAndOrderQuery($filters, $order)->getResult();
    }

    public function findAll()
    {
        return $this->getRepository()->findAll();
    }
    
    public function findBy($criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    public function findOneBy($criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    public function find($id)
    {
        return $this->getRepository()->find($id);
    }
    
    abstract function getRepositoryName();

}
