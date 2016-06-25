<?php

namespace Tesis\AdminBundle\Tests\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use Tesis\AdminBundle\Entity\Repository\MapaRecorridoRepository;
use Tesis\AdminBundle\Manager\MapaRecorridoManager;

class MapaRecorridoManagerConServiciosTest extends TestCase
{
    /**
     * @var $manager MapaRecorridoManager
     */
    private $manager;

    public function setUp()
    {
        $this->manager = new MapaRecorridoManager();

        $mapas = MapaRecorridoMock::$currentMapWithServices;

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
     * MapaRecorridoManager::getUbicacionServicio()
     */
    public function testGetUbicacionServicio()
    {
        $servicios = $this->manager->getUbicacionServicio(1);
        $this->assertEquals(1, count($servicios));

        $servicios = $this->manager->getUbicacionServicio(100);
        $this->assertEquals(0, count($servicios));
    }

    /**
     * Obtiene la distancia minima a un servicio en el lado derecho del arco
     * y debe devolver la orientación correcta segun el punto de partida from o to del arco
     *
     * MapaRecorridoManager::getDistanciaAlServicio
     */
    public function testGetDistanciaAlServicioLadoDerecho()
    {
        $arco = array(
            'from' => 1,
            'to' => 3,
            'id' => 'd72801a9-c237-4d32-a186-ddf1342a9adc',
            'distancia' => 10,
            'infRef' => 'Arco Horizontal 1',
            'lugares' => array(
                'izq' => array(),
                'der' => array(
                    array(
                        'idCategoria' => '1',
                        'idServicio' => '1',
                        'categoria' => 'Boleteria',
                        'servicio' => 'Flecha Bus',
                        'distancia' => '2',
                    ),
                ),
            ),
        );

        $tipoNodo = 'from';
        $idServicio = '1';
        $resultado = $this->manager->getDistanciaAlServicio($tipoNodo, $idServicio, $arco);
        $distanciaMin = $resultado['distancia'];
        $direccion = $resultado['direccion'];
        $this->assertEquals(2, $distanciaMin);
        $this->assertEquals('der', $direccion);

        $tipoNodo = 'to';
        $idServicio = '1';
        $resultado = $this->manager->getDistanciaAlServicio($tipoNodo, $idServicio, $arco);
        $distanciaMin = $resultado['distancia'];
        $direccion = $resultado['direccion'];
        $this->assertEquals(8, $distanciaMin);
        $this->assertEquals('izq', $direccion);
    }

    /**
     * Obtiene la distancia minima a un servicio en el lado izquierdo del arco
     * y debe devolver la orientación correcta segun el punto de partida from o to del arco
     *
     * MapaRecorridoManager::getDistanciaAlServicio
     */
    public function testGetDistanciaAlServicioLadoIzquierdo()
    {
        $arco = array(
            'from' => 1,
            'to' => 3,
            'id' => 'd72801a9-c237-4d32-a186-ddf1342a9adc',
            'distancia' => 10,
            'infRef' => 'Arco Horizontal 1',
            'lugares' => array(
                'izq' => array(
                    array(
                        'idCategoria' => '1',
                        'idServicio' => '1',
                        'categoria' => 'Boleteria',
                        'servicio' => 'Flecha Bus',
                        'distancia' => '2',
                    ),
                ),
                'der' => array(),
            ),
        );

        $tipoNodo = 'from';
        $idServicio = '1';
        $resultado = $this->manager->getDistanciaAlServicio($tipoNodo, $idServicio, $arco);
        $distanciaMin = $resultado['distancia'];
        $direccion = $resultado['direccion'];
        $this->assertEquals(2, $distanciaMin);
        $this->assertEquals('izq', $direccion);

        $tipoNodo = 'to';
        $idServicio = '1';
        $resultado = $this->manager->getDistanciaAlServicio($tipoNodo, $idServicio, $arco);
        $distanciaMin = $resultado['distancia'];
        $direccion = $resultado['direccion'];
        $this->assertEquals(8, $distanciaMin);
        $this->assertEquals('der', $direccion);
    }

    /**
     * Obtiene la distancia minima a un servicio en el lado derecho del arco
     * y debe devolver la orientación correcta segun el punto de partida from o to del arco
     *
     * MapaRecorridoManager::getDistanciaAlServicio
     */
    public function testGetDistanciaAlServicioLadoDerechoConServicioRepetido()
    {
        $arco = array(
            'from' => 1,
            'to' => 3,
            'id' => 'd72801a9-c237-4d32-a186-ddf1342a9adc',
            'distancia' => 10,
            'infRef' => 'Arco Horizontal 1',
            'lugares' => array(
                'izq' => array(),
                'der' => array(
                    array(
                        'idCategoria' => '1',
                        'idServicio' => '1',
                        'categoria' => 'Boleteria',
                        'servicio' => 'Flecha Bus',
                        'distancia' => '2',
                    ),
                    array(
                        'idCategoria' => '1',
                        'idServicio' => '1',
                        'categoria' => 'Boleteria',
                        'servicio' => 'Flecha Bus',
                        'distancia' => '4',
                    ),
                    array(
                        'idCategoria' => '1',
                        'idServicio' => '1',
                        'categoria' => 'Boleteria',
                        'servicio' => 'Flecha Bus',
                        'distancia' => '6',
                    ),
                ),
            ),
        );

        $tipoNodo = 'from';
        $idServicio = '1';
        $resultado = $this->manager->getDistanciaAlServicio($tipoNodo, $idServicio, $arco);
        $distanciaMin = $resultado['distancia'];
        $direccion = $resultado['direccion'];
        $this->assertEquals(2, $distanciaMin);
        $this->assertEquals('der', $direccion);

        $tipoNodo = 'to';
        $idServicio = '1';
        $resultado = $this->manager->getDistanciaAlServicio($tipoNodo, $idServicio, $arco);
        $distanciaMin = $resultado['distancia'];
        $direccion = $resultado['direccion'];
        $this->assertEquals(4, $distanciaMin);
        $this->assertEquals('izq', $direccion);
    }

    /**
     * Obtiene la distancia minima a un servicio en el lado izquierdo del arco
     * y debe devolver la orientación correcta segun el punto de partida from o to del arco
     *
     * MapaRecorridoManager::getDistanciaAlServicio
     */
    public function testGetDistanciaAlServicioLadoIzquierdoConServicioRepetido()
    {
        $arco = array(
            'from' => 1,
            'to' => 3,
            'id' => 'd72801a9-c237-4d32-a186-ddf1342a9adc',
            'distancia' => 10,
            'infRef' => 'Arco Horizontal 1',
            'lugares' => array(
                'izq' => array(
                    array(
                        'idCategoria' => '1',
                        'idServicio' => '1',
                        'categoria' => 'Boleteria',
                        'servicio' => 'Flecha Bus',
                        'distancia' => '2',
                    ),
                    array(
                        'idCategoria' => '1',
                        'idServicio' => '1',
                        'categoria' => 'Boleteria',
                        'servicio' => 'Flecha Bus',
                        'distancia' => '4',
                    ),
                    array(
                        'idCategoria' => '1',
                        'idServicio' => '1',
                        'categoria' => 'Boleteria',
                        'servicio' => 'Flecha Bus',
                        'distancia' => '6',
                    ),
                ),
                'der' => array(),
            ),
        );

        $tipoNodo = 'from';
        $idServicio = '1';
        $resultado = $this->manager->getDistanciaAlServicio($tipoNodo, $idServicio, $arco);
        $distanciaMin = $resultado['distancia'];
        $direccion = $resultado['direccion'];
        $this->assertEquals(2, $distanciaMin);
        $this->assertEquals('izq', $direccion);

        $tipoNodo = 'to';
        $idServicio = '1';
        $resultado = $this->manager->getDistanciaAlServicio($tipoNodo, $idServicio, $arco);
        $distanciaMin = $resultado['distancia'];
        $direccion = $resultado['direccion'];
        $this->assertEquals(4, $distanciaMin);
        $this->assertEquals('der', $direccion);
    }

    /**
     * Obtiene la distancia minima a un servicio que se encuentra en ambos lados del arco
     * debe devolver la orientación correcta segun el punto de partida from o to del arco y
     * la distancia minima
     *
     * MapaRecorridoManager::getDistanciaAlServicio
     */
    public function testGetDistanciaAlServicioConServicioRepetidoEnAmbosLados()
    {
        $arco = array(
            'from' => 1,
            'to' => 3,
            'id' => 'd72801a9-c237-4d32-a186-ddf1342a9adc',
            'distancia' => 10,
            'infRef' => 'Arco Horizontal 1',
            'lugares' => array(
                'izq' => array(
                    array(
                        'idCategoria' => '1',
                        'idServicio' => '1',
                        'categoria' => 'Boleteria',
                        'servicio' => 'Flecha Bus',
                        'distancia' => '2',
                    ),
                ),
                'der' => array(
                    array(
                        'idCategoria' => '1',
                        'idServicio' => '1',
                        'categoria' => 'Boleteria',
                        'servicio' => 'Flecha Bus',
                        'distancia' => '6',
                    ),
                ),
            ),
        );

        $tipoNodo = 'from';
        $idServicio = '1';
        $resultado = $this->manager->getDistanciaAlServicio($tipoNodo, $idServicio, $arco);
        $distanciaMin = $resultado['distancia'];
        $direccion = $resultado['direccion'];
        $this->assertEquals(2, $distanciaMin);
        $this->assertEquals('izq', $direccion);

        $tipoNodo = 'to';
        $idServicio = '1';
        $resultado = $this->manager->getDistanciaAlServicio($tipoNodo, $idServicio, $arco);
        $distanciaMin = $resultado['distancia'];
        $direccion = $resultado['direccion'];
        $this->assertEquals(4, $distanciaMin);
        $this->assertEquals('izq', $direccion);
    }

    /**
     * De una secuencia valida de nodos, obtiene un array de indicaciones minima
     * Indicaciones minima significa que los arcos que tienen la misma dirección se unen.
     *
     * MapaRecorridoManager::getArrayIndicaciones
     */
    public function testGetArrayIndicaciones()
    {
        $indicacionesMinimas = $this->manager->getArrayIndicaciones(array(1));
        $this->assertEquals(1, count($indicacionesMinimas));

        $indicacionesMinimas = $this->manager->getArrayIndicaciones(array(1, 3));
        $this->assertEquals(2, count($indicacionesMinimas));

        $indicacionesMinimas = $this->manager->getArrayIndicaciones(array(1, 3, 4));
        $this->assertEquals(3, count($indicacionesMinimas));

        $indicacionesMinimas = $this->manager->getArrayIndicaciones(array(1, 3, 4, 5));
        $this->assertEquals(4, count($indicacionesMinimas));

        $indicacionesMinimas = $this->manager->getArrayIndicaciones(array(1, 3, 4, 5, 6));
        $this->assertEquals(4, count($indicacionesMinimas));

        $indicacionesMinimas = $this->manager->getArrayIndicaciones(array(1, 3, 4, 5, 6, 7));
        $this->assertEquals(5, count($indicacionesMinimas));
    }
}
