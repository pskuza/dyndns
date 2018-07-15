<?php
declare(strict_types=1);

namespace pskuza\dyndns\providers;

class cloudflare implements dns
{
    protected $cloudflare;

    public function __construct(\Noodlehaus\Config $config)
    {
        $key     = new Cloudflare\API\Auth\APIKey($config->get('cloudflare.email'), $config->get('cloudflare.key'));
        $adapter = new Cloudflare\API\Adapter\Guzzle($key);
        $this->cloudflare = new Cloudflare\API\Endpoints\DNS($adapter);
    }

    public function create_record(): bool
    {

    }

    public function update_record(): bool
    {
    }
}