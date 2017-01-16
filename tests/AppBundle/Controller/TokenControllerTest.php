<?php

namespace tests\AppBundle\Controller;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

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
        $response = $this->client->post('/api/token', [
            'auth' => ['admin', 'reallysafepassword']
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }
}
