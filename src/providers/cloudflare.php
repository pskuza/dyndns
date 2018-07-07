<?php
declare(strict_types=1);

namespace pskuza\dyndns\providers;

class cloudflare implements dns
{
    public function __construct(string $email, string $apikey)
    {
        $key     = new Cloudflare\API\Auth\APIKey($email, $apikey);
        $adapter = new Cloudflare\API\Adapter\Guzzle($key);
        $user    = new Cloudflare\API\Endpoints\User($adapter);
    }

    public function create_record()
    {

    }

    public function update_record()
    {
    }
}