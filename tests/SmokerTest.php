<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokerTest extends WebTestCase
{
    public function testSomething()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $link = $crawler->filter(".question-container h2 a")->first()->link();
        $client->click($link);
        $this->assertResponseIsSuccessful();
        // $this->assertSelectorTextContains('h1', 'Hello World');
        $crawler = $client->request('GET', '/admin/user');
        $this->assertTrue($client->getResponse()->isRedirection());

    }

    public function testUserSecuredUrls()
    { // je crée un client qui a chaque requete va envoyer des entête pour etre connecté en tant que Gertrude
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'gertrude',
            'PHP_AUTH_PW'   => 'gertrude',
        ]);

        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();

        $this->assertCount(1,$crawler->filter('.navbar a:contains(\'Poser une question\')'));
        $this->assertCount(1,$crawler->filter('.navbar a:contains(\'Mon compte\')'));

        $client->request( 'GET', "/admin/user");
        $this->assertTrue($client->getResponse()->isForbidden());
    }

    public function testAdminSecuredUrls()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'claire',
            'PHP_AUTH_PW'   => 'claire',
        ]);

        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    public function testModeratorSecuredUrls()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'micheline',
            'PHP_AUTH_PW'   => 'micheline',
        ]);

        $client->request("GET", "/admin/user");
        $this->assertResponseIsSuccessful();
    }
}
