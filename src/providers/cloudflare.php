<?php
declare(strict_types=1);

namespace pskuza\dyndns\providers;

class cloudflare implements dns
{
    public function __construct(\Noodlehaus\Config $config)
    {
//        $key     = new Cloudflare\API\Auth\APIKey($email, $apikey);
//        $adapter = new Cloudflare\API\Adapter\Guzzle($key);
//        $user    = new Cloudflare\API\Endpoints\User($adapter);
    }

    public function create_record(): bool
    {

    }

    public function update_record(): bool
    {
    }
}