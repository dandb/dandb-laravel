<?php namespace Credibility\DandB;

use Exception;

class DandBLaravel {

    /** @var DandB  */
    protected $dandb;

    protected $cache;

    protected $config;

    public function __construct(DandB $dandb, $app)
    {
        $this->dandb = $dandb;
        $this->cache = $app->make('cache');
        $this->config =  $app->make('config');
    }

    /**
     * Calls DandB methods while automatically wrapping cache for access token.
     *
     * @param $name
     * @param $arguments
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        if(method_exists($this->dandb, $name)) {
            $key = $this->getAccessTokenCacheKey();
            if ($this->cache->has($key)) {
                $accessToken = $this->cache->get($key);
            } else {
                $accessToken = $this->dandb->getAccessToken();
                if ($accessToken) {
                    $this->cache->put($key, $accessToken, $this->config->get('dandb-laravel::cache_ttl'));
                } else {
                    throw new Exception('GET Access Token failed');
                }
            }

            $arguments[] = $accessToken;

            return call_user_func_array(
                array($this->dandb, $name),
                $arguments
            );
        } else {
            throw new Exception('Function does not exist in DandB');
        }
    }

    public function getAccessTokenCacheKey()
    {
        $clientId = $this->config->get('dandb-laravel::client_id');
        $secret = $this->config->get('dandb-laravel::client_secret');
        return 'owl_access_' . $clientId . '_' . $secret;
    }

}