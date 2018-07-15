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
    protected $provider;

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

        //read config and setup provider
        $provider_config = '\\pskuza\dyndns\providers\\' . $this->config->get('dyndns.provider');
        if (class_exists($provider_config)) {
            $this->provider = new $provider_config($this->config);
        } else {
            $this->error(500, 'The configured provider does not exist.');
        }
    }

    public function update(): array {
        return [true, 200, "update was called"];
    }

    public function register(): array {
        if($this->config->get('dyndns.allow_register') === true) {
            if(empty($this->config->get('dyndns.register_token'))) {
                throw new InvalidSetup("dyndns was not setup correctly the register_token is not set. Run $ openssl rand -hex 32 and paste it into the config.");
            } else {
                return [true, 200, "register was called"];
            }
        }
        return [false, 403, "Registering is disabled in the config."];
    }

    private function get_ip(): string {
        // ipv4=auto should use the IP from $_SERVER, else take from the get query
        // ipv6=auto should use the IP from $_SERVER, else take from the get query
        $header = $_SERVER['X-Real-IP'];
        if(filter_var($header, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) || filter_var($header, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return $header;
        }
        throw new InvalidSetup("dyndns was not setup correctly and can't retrieve the ip. Check the reverse proxy configuration.");
    }

    public function error(int $http_code, string $error_message) {
        http_response_code($http_code);
        error_log($error_message, 0);
        die($error_message);
    }

    public function success(int $http_code = 200, string $message) {
        http_response_code($http_code);
        die($message);
    }
}