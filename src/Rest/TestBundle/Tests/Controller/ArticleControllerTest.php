<?php

namespace Rest\TestBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleControllerTest extends WebTestCase
{
    public function testGetarticle()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getArticle');
    }

    public function testGetarticles()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getArticles');
    }

    public function testPutarticle()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/putArticle');
    }

}
