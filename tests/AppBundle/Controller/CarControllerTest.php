<?php

namespace tests\AppBundle\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

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
        $response = $this->client->request('GET', 'car');
        $this->assertEquals(401, $response->getStatusCode());
    }
}
