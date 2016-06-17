<?php

namespace Tesis\AdminBundle\Tests\Manager;

use Tesis\AdminBundle\Entity\Repository\MapaRecorridoRepository;
use Tesis\AdminBundle\Manager\MapaRecorridoManager;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectManager;

class MapaRecorridoManagerTest extends TestCase
{
    private $manager;

    public function setUp()
    {
        $this->manager = new MapaRecorridoManager();

        $mapas = MapaRecorridoMock::$currentMap;

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
     * @covers MapaRecorridoManager::getArco
     */
    public function testGetArco_ConNodosAdyacentes_DeberiaDevolverArco()
    {
        $this->assertTrue(is_array($this->manager->getCurrentMap()));

        $arco = $this->manager->getArco(1, 2);
        $this->assertEquals('d72801a9-c237-4d32-a186-ddf1342a9adc', $arco['id']);

        $arco = $this->manager->getArco(2, 1);
        $this->assertEquals('d72801a9-c237-4d32-a186-ddf1342a9adc', $arco['id']);
    }

    /**
     * @covers MapaRecorridoManager::getArco
     */
    public function testGetArco_ConNodoRepetido_DeberiaDevolverArrayVacio()
    {
        $arco = $this->manager->getArco(1, 1);
        $this->assertEmpty($arco);
    }

    /**
     * @covers MapaRecorridoManager::getNodo
     */
    public function testGetNodo_ConNodoExistente_DeberiaDevolverNodo()
    {
        $nodo = $this->manager->getNodo(1);
        $this->assertTrue(is_array($nodo));
    }

    /**
     * @covers MapaRecorridoManager::getNodo
     */
    public function testGetNodo_ConNodoInexistente_DeberiaDevolverNulo()
    {
        $nodo = $this->manager->getNodo(10);
        $this->assertNull($nodo);
    }

    /**
     * @covers MapaRecorridoManager::getDistanciaEntreNodosAdyacentes
     */
    public function testGetDistanciaEntreNodosAdyacentes_DebeDevolverDistancia()
    {
        $distancia = $this->manager->getDistanciaEntreNodosAdyacentes(1, 2);
        $this->assertEquals(10, $distancia);
    }

    /**
     * @covers MapaRecorridoManager::getDistanciaEntreNodosAdyacentes
     */
    public function testGetDistanciaEntreNodosNoAdyacentes_DebeDevolverNulo()
    {
        $distancia = $this->manager->getDistanciaEntreNodosAdyacentes(5, 3);
        $this->assertNull($distancia);
    }

    /**
     * @covers MapaRecorridoManager::getDireccionEntreNodosAdyacentes
     */
    public function testGetDireccion_ConNodosAdyacentes_DebeDevolverDireccion()
    {
        $direccion = $this->manager->getDireccionEntreNodosAdyacentes(1, 2);
        $this->assertEquals('izq', $direccion);

        $direccion = $this->manager->getDireccionEntreNodosAdyacentes(2, 1);
        $this->assertEquals('der', $direccion);

        $direccion = $this->manager->getDireccionEntreNodosAdyacentes(1, 4);
        $this->assertEquals('abj', $direccion);

        $direccion = $this->manager->getDireccionEntreNodosAdyacentes(4, 1);
        $this->assertEquals('arr', $direccion);
    }

    /**
     * @covers MapaRecorridoManager::getDireccionEntreNodosAdyacentes
     */
    public function testGetDireccion_ConNodosNoAdyacentes_DebeDevolverNulo()
    {
        $direccion = $this->manager->getDireccionEntreNodosAdyacentes(1, 3);
        $this->assertNull($direccion);
    }

    /**
     * @cover MapaRecorridoManager::getDistanciaTotalEntreNodos
     */
    public function testGetDistanciaTotalEntreNodos_ConSecuenciaNodosValida_DeberiaDevolverLaDistanciaTotal()
    {
        $distanciaTotal = $this->manager->getDistanciaTotalEntreNodos(array(1, 2));
        $this->assertEquals(10, $distanciaTotal);

        $distanciaTotal = $this->manager->getDistanciaTotalEntreNodos(array(1, 2, 3, 4));
        $this->assertEquals(25, $distanciaTotal);

        $distanciaTotal = $this->manager->getDistanciaTotalEntreNodos(array(4, 3, 2, 1));
        $this->assertEquals(25, $distanciaTotal);

        $distanciaTotal = $this->manager->getDistanciaTotalEntreNodos(array(1));
        $this->assertEquals(0, $distanciaTotal);
    }

    /**
     * @cover MapaRecorridoManager::getDistanciaTotalEntreNodos
     */
    public function testGetDistanciaTotalEntreNodos_ConSecuenciaNodosInvalida_DeberiaLanzarExcepcion()
    {
//        TODO: Implementar Prueba.
        $this->markTestIncomplete('La funcion deberia Lanzar excepcion en caso de secuencia invalida.');
    }


    /**
     * @codeCoverageIgnore
     */
    public function testGetDistanciaEntreNodosAdyacentes()
    {
        $stub = $this->createMock(MapaRecorridoManager::class);
        $stub->method('getCurrentMap')
            ->willReturn(array());

        $this->assertEquals(0, count($stub->getCurrentMap()));
    }

    /**
     * Prueba de Giro a partir de una dirección inicial y otra final
     */
    public function testGetRotacion()
    {
        $inicio = "izq";
        $fin = "arr";
        $resultado = $this->invokeMethod($this->manager, 'getRotacion', array($inicio, $fin));

        $this->assertEquals("derecha", $resultado);

        $inicio = "der";
        $fin = "arr";
        $resultado = $this->invokeMethod($this->manager, 'getRotacion', array($inicio, $fin));

        $this->assertEquals("izquierda", $resultado);

        $inicio = "izq";
        $fin = "izq";
        $resultado = $this->invokeMethod($this->manager, 'getRotacion', array($inicio, $fin));

        $this->assertNull($resultado);

    }

    /**
     * Función para realizar Test de Metodos Privados
     *
     * @param $object
     * @param $methodName
     * @param array $parameters
     * @return mixed
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);

    }
}
