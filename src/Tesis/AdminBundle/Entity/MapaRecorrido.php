<?php

namespace Tesis\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MapaRecorrido
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Tesis\AdminBundle\Entity\Repository\MapaRecorridoRepository")
 */
class MapaRecorrido
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaModificacion", type="datetime")
     */
    private $fechaModificacion;

    /**
     * @var array
     *
     * @ORM\Column(name="mapaJson", type="json_array")
     */
    private $mapaJson;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fechaModificacion
     *
     * @param \DateTime $fechaModificacion
     *
     * @return MapaRecorrido
     */
    public function setFechaModificacion($fechaModificacion)
    {
        $this->fechaModificacion = $fechaModificacion;

        return $this;
    }

    /**
     * Get fechaModificacion
     *
     * @return \DateTime
     */
    public function getFechaModificacion()
    {
        return $this->fechaModificacion;
    }

    /**
     * Set mapaJson
     *
     * @param array $mapaJson
     *
     * @return MapaRecorrido
     */
    public function setMapaJson($mapaJson)
    {
        $this->mapaJson = $mapaJson;

        return $this;
    }

    /**
     * Get mapaJson
     *
     * @return array
     */
    public function getMapaJson()
    {
        return $this->mapaJson;
    }
}
