<?php

namespace Tesis\AdminBundle\Tests\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use Tesis\AdminBundle\Entity\Repository\MapaRecorridoRepository;
use Tesis\AdminBundle\Manager\MapaRecorridoManager;

class RecorridoTest extends TestCase
{
    /** @var  MapaRecorridoManager */
    private $manager;

    public function setUp()
    {
        $mapas = MapaRecorridoMock::$currentMapWithServicesInEachEdges;

        $mapaRecorridoRepository = $this
            ->getMockBuilder(MapaRecorridoRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mapaRecorridoRepository
            ->expects($this->any())
            ->method('findCurrentMap')
            ->will($this->returnValue($mapas));

        $entityManagerMock = $this
            ->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();;
        $entityManagerMock
            ->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($mapaRecorridoRepository));

        $this->manager = new MapaRecorridoManager();
        $this->manager->setEntityManager($entityManagerMock);
    }

    public function tearDown()
    {
        $this->manager = null;
    }

    /**
     * Pruba de la cantidad de instrucciones a traves del grafo para ir de un nodo inicio a un nodo final
     */
    public function testGetArrayIndicaciones()
    {
        $secuenciaNodos = array(1, 2);
        $arrayIndicaciones = $this->manager->getArrayIndicaciones($secuenciaNodos);
        $cantidadInstrucciones = 1 + 1;
        $this->assertEquals($cantidadInstrucciones, count($arrayIndicaciones));

        $secuenciaNodos = array(1, 2, 3);
        $arrayIndicaciones = $this->manager->getArrayIndicaciones($secuenciaNodos);
        $cantidadInstrucciones = 1 + 1;
        $this->assertEquals($cantidadInstrucciones, count($arrayIndicaciones));

        $secuenciaNodos = array(1, 2, 3, 4, 5, 6, 7, 8);
        $arrayIndicaciones = $this->manager->getArrayIndicaciones($secuenciaNodos);
        $cantidadInstrucciones = 1 + 5; // 5 arcos rectos
        $this->assertEquals($cantidadInstrucciones, count($arrayIndicaciones));

        $secuenciaNodos = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
        $arrayIndicaciones = $this->manager->getArrayIndicaciones($secuenciaNodos);
        $cantidadInstrucciones = 1 + 7; // 7 arcos rectos
        $this->assertEquals($cantidadInstrucciones, count($arrayIndicaciones));
    }

    public function testGetRutaCortaAlServicio_ServicioUbicadoEnElUnicoArco_DeberiaDevolverInstrucciones()
    {
        $posicionActual = 1;
        $idServicio = 1;
        $rutaMasCorta = $this->manager->getRutaCortaAlServicio($posicionActual, $idServicio);
        $this->assertEquals(2, count($rutaMasCorta));
    }

    public function testGetRutaCortaAlServicio_ServicioUbicadoArcoLineal_DeberiaDevolverInstrucciones()
    {
        $posicionActual = 1;
        $idServicio = 2;
        $rutaMasCorta = $this->manager->getRutaCortaAlServicio($posicionActual, $idServicio);
        $this->assertEquals(2, count($rutaMasCorta));
    }

    public function testGetRutaCortaAlServicio_ServicioUbicadoEnArcoPerpendicular_DeberiaDevolverInstrucciones()
    {
        $posicionActual = 1;
        $idServicio = 3;
        $rutaMasCorta = $this->manager->getRutaCortaAlServicio($posicionActual, $idServicio);
        $this->assertEquals(4, count($rutaMasCorta));
    }

}
