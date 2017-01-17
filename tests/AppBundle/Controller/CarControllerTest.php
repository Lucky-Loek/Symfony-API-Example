<?php

namespace tests\AppBundle\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class CarControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        $this->client = new Client([
            'base_uri' => 'http://symfony.app',
        ]);
    }

    /**
     * @test
     */
    public function shouldThrow401OnUnauthorizedUserRequest()
    {
        $this->expectException(ClientException::class);
        $this->client->request('GET', 'car');
    }
}
