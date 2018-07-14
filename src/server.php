<?php
declare(strict_types=1);

namespace pskuza\dyndns;
use Doctrine\Common\Cache\ApcuCache;
use Noodlehaus\Config;

class server
{
    protected $db;
    protected $cache;
    protected $config;

    public function __construct(string $config, string $db)
    {
        try {
            $this->db = \ParagonIE\EasyDB\Factory::create(
                'sqlite:' . $db
            );
        } catch (\Exception $e) {
            $this->error(500, 'No database connection.');
        }

        try {
            $this->cache = new ApcuCache();
        } catch (\Exception $e) {
            $this->error(500, 'Could not create cache.');
        }

        try {
            $this->config = new Config($config);
        } catch (\Exception $e) {
            $this->error(500, 'Could not read config.');
        }
    }

    public function update(): array {
        return [true, 200, "update was called"];
    }

    public function error(int $http_code, string $error_message) {
        http_response_code($http_code);
        die($error_message);
    }

    public function success(int $http_code = 200, string $message) {
        http_response_code($http_code);
        die($message);
    }
}