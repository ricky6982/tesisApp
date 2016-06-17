<?php

namespace Tesis\AdminBundle\Tests;

use Tesis\AdminBundle\Manager\MapaRecorridoManager;

class Test extends \PHPUnit_Framework_TestCase
{
    private $manager;

    public function setUp()
    {
        $this->manager = new MapaRecorridoManager();
    }

    public function tearDown()
    {
        $this->manager = NULL;
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
