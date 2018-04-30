<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testShowUsers()
    {
        $client = static::createClient();

        //$crawler = $client->request('GET', '/users');
        $crawler = $client->getResponse()->getContent();

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'The "Content-Type" header is "application/json"'
        );

        $this->assertContains('users', $crawler);
        $this->assertTrue($client->getResponse()->isNotFound());
    }

    public function testAddUser(){
        $client = static::createClient();

        $crawler = $client->getResponse()->getContent();

        $client->request(
            'POST',
            '/user/new',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"username":"usertest", "password":"usertest", "email":"user@mail.fr"}'
        );
    }
}
