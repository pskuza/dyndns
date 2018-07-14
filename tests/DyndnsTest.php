<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class RSSTest extends TestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = new Client(['base_uri' => 'http://127.0.0.1/', 'http_errors' => false]);
    }

    public function testConnection()
    {
        $r = $this->client->request('GET', '/');
        $this->assertEquals(200, $r->getStatusCode());
    }
}