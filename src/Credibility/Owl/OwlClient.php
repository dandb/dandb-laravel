<?php

namespace Credibility\Owl;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

use Guzzle\Http\Client;

/**
 * Class OwlClient
 * @package Crediblity
 */
class OwlClient extends \Guzzle\Http\Client{

    private $_config;

    public function __construct(){
        // Create Guzzle Client
        parent::__construct();

        $this->_config = app()['config'];

        $this->setBaseUrl($this->_config->get('owl::base_url'));

        // Get and set access token as default query param
        $this->setDefaultOption('headers',  array('access-token' => $this->getAccessToken()));

        // Create Guzzle Client
        $this->client = new Client($this->app['config']->get(''));
    }

    private function getAccessToken()
    {
        $client_id = $this->_config->get('owl::client_id');
        $client_secret = $this->_config->get('owl::client_secret');

        // Check the Cache for an existing Access Token
        $cacheKey = 'owl-access-'.$client_id.'-'.$client_secret;

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        } else {
            $url = '/v1/oauth/token';
            $params = array(
                'query' => array(
                    'grant_type' => 'client_credentials',
                    'client_id' => $client_id,
                    'client_secret' => $client_secret,
                )
            );

            $response = $this->_owlGet($url, $params);
            Cache::put($cacheKey, $response['access_token'], 24*60);

            return $response['access_token'];
        }
    }

    public function userRegisterProduct($email, $password, $firstName, $lastName, $duns, $productId, $acceptedTos, $source )
    {
        $url = '/v1.1/user/register-product';
        $params = array(
            'email' => $email,
            'password' => $password,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'duns' => $duns,
            'product_id' => $productId,
            'accepted_tos' => $acceptedTos,
            'source' => $source
        );

        return $this->_owlPost($url, $params);

    }

    public function userLogin($email, $password)
    {
        $url = '/v1/user/token';
        $params = array(
            'email' => $email,
            'password' => $password
        );
        return $this->_owlPost($url, $params);
    }

    public function userEntitlements()
    {
        $url = '/v1.1/user/entitlements';
        $params =  array(
            'query' => array(
                'user_token' => Session::get('user_token')
            )
        );

        $response = $this->_owlGet($url, $params);

        if ( $response['meta']['code'] == 200 ) {
            return $response['response']['entitlements'];
        }
        return array();
    }

    /* HELPER FUNCTIONS */

    /**
     * HTTP PUT
     * @param  string $url
     * @param  array  $vars
     * @return string json encoded response
     */
    private function _owlPut($url, $vars)
    {
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];
        $request = $this->put($url, compact('headers'), $vars);
        $response = $request->send()->json();
        // Following if statement is for debugging purposes
        //$this->_owlLog(PHP_EOL.'PUT URL:'.$url.PHP_EOL.'PARAMS:'.json_encode($vars,1).PHP_EOL.'OWL RESPONSE:'.json_encode($response,1).PHP_EOL);
        return $response;
    }

    /**
     * HTTP GET
     * @param  string $url
     * @param  array  $vars
     * @return string json encoded response
     */
    private function _owlGet($url, $vars) {
        $request = $this->get($url, array(), $vars);
        $response = $request->send()->json();
        // Following if statement is for debugging purposes
        //$this->_owlLog(PHP_EOL.'GET URL:'.$url.PHP_EOL.'PARAMS:'.json_encode($vars,1).PHP_EOL.'OWL RESPONSE:'.json_encode($response,1).PHP_EOL);
        return $response;
    }

    /**
     * HTTP POST
     * @param  string $url
     * @param  array  $vars
     * @return string json encoded response
     */
    private function _owlPost($url, $vars) {
        $request = $this->post($url, array(), $vars);
        $response = $request->send()->json();
        // Following if statement is for debugging purposes
        //$this->_owlLog(PHP_EOL.'POST URL:'.$url.PHP_EOL.'PARAMS:'.json_encode($vars,1).PHP_EOL.'OWL RESPONSE:'.json_encode($response,1).PHP_EOL);
        return $response;
    }

    /**
     * Log string to owl_client.log
     * For development purposes only!
     * This should not be run in production environments
     * @param  string $str
     * @return null
     */
    private function _owlLog($str) {
        //triple check that logs don't get written in production environment
        if(isset($_SERVER['APP_ENV']) && strtolower($_SERVER['APP_ENV']) !== 'prd'){
            $log = new \Monolog\Logger('owl_client');
            $log->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__. '/../../../storage/logs/owl_client.log', \Monolog\Logger::DEBUG));
            $log->addDebug($str);
        }
    }
}