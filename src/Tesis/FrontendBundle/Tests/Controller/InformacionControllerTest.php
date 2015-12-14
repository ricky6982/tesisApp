<?php

namespace Tesis\FrontendBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InformacionControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

    public function testEdificio()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/edificio');
    }

    public function testServicios()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/servicios');
    }

    public function testComollegar()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/comoLlegar');
    }

    public function testBuscar()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/buscar');
    }

}
