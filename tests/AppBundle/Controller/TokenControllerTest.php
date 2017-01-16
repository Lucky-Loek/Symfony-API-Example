<?php

namespace tests\AppBundle\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

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
        $request = new Request(
            'POST',
            '/api/token',
            [
                'username' => 'admin',
                'password' => 'unsafepassword'
            ]
        );

        $response = $this->client->send($request);

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
        $this->expectException(ClientException::class);

        $request = new Request(
            'POST',
            '/api/token',
            [
                'username' => 'admin',
                'password' => 'reallysafepassword'
            ]
        );

        $response = $this->client->send($request);
    }
}
