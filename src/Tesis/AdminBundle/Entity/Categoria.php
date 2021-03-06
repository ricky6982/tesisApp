<?php

namespace Tesis\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categoria
 *
 * @ORM\Table
 * @ORM\Entity(repositoryClass="Tesis\AdminBundle\Entity\Repository\CategoriaRepository")
 */
class Categoria
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="categorias")
     */
    private $local;

    /**
     * @ORM\OneToMany(targetEntity="Producto", mappedBy="categoria", cascade={"remove"})
     */
    private $productos;

    public function __toString()
    {
        return $this->nombre;
    }


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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Categoria
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add producto
     *
     * @param \Tesis\AdminBundle\Entity\Producto $producto
     *
     * @return Categoria
     */
    public function addProducto(\Tesis\AdminBundle\Entity\Producto $producto)
    {
        $this->productos[] = $producto;

        return $this;
    }

    /**
     * Remove producto
     *
     * @param \Tesis\AdminBundle\Entity\Producto $producto
     */
    public function removeProducto(\Tesis\AdminBundle\Entity\Producto $producto)
    {
        $this->productos->removeElement($producto);
    }

    /**
     * Get productos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductos()
    {
        return $this->productos;
    }

    /**
     * Set local
     *
     * @param \Tesis\AdminBundle\Entity\Usuario $local
     *
     * @return Categoria
     */
    public function setLocal(\Tesis\AdminBundle\Entity\Usuario $local = null)
    {
        $this->local = $local;

        return $this;
    }

    /**
     * Get local
     *
     * @return \Tesis\AdminBundle\Entity\Usuario
     */
    public function getLocal()
    {
        return $this->local;
    }
}
