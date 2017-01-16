<?php

namespace tests\AppBundle\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;

class TokenControllerTest extends \PHPUnit_Framework_TestCase
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
    public function shouldCreateTokenOnValidCredentials()
    {
        $response = $this->client->post('/api/token', [
            'auth' => ['admin', 'unsafepassword']
        ]);

        $body = $response->getBody()->getContents();
        $body = json_decode($body);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertObjectHasAttribute('token', $body);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionOnInvalidCredentials()
    {
        $this->expectException(ServerException::class);

        $this->client->post('/api/token', [
            'auth' => ['admin', 'reallysafepassword']
        ]);
    }
}
