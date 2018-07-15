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

    public function testRegister() {
        //register set to true, but no token set
        $r = $this->client->request('GET', '/register');
        $this->assertEquals(500, $r->getStatusCode());

        //register set to false
        shell_exec('sed -i s/allow_register = true/allow_register = false/g /data/config.ini');
        $r = $this->client->request('GET', '/register');
        $this->assertEquals(403, $r->getStatusCode());

        //register set to true and token set
        shell_exec('sed -i s/allow_register = false/allow_register = true/g /data/config.ini');
        shell_exec('sed -i s/register_token =/register_token ='.bin2hex(random_bytes(32)).'/g /data/config.ini');
    }
}