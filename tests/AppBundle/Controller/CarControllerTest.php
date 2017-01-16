<?php

namespace tests\AppBundle\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;

class CarControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    protected $client;

    public function setUp() {
        $this->client = new Client([
            'base_uri' => 'http://symfony.app'
        ]);
    }

    /**
     * @test
     */
    public function shouldThrow401OnUnauthorizedUserRequest() {
        $this->expectException(ServerException::class);
        $this->client->request('GET', 'car');
    }
}
