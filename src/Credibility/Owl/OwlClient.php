<?php

namespace Credibility\Owl;

use Illuminate\Support\Facades\Cache;

use Guzzle\Http\Client;

/**
 * Class OwlClient
 * @package Crediblity
 */
class OwlClient {

    private $app;
    private $client;

    public function __construct(){

        $this->app = app();

        // Create Guzzle Client
        $this->client = new Client($this->app['config']->get('owl::base_url'));

        // Get and set access token as default query param
        $this->client->setDefaultOption('query', array(
                'access_token' => $this->getAccessToken(
                    $this->app['config']->get('owl::client_id'),
                    $this->app['config']->get('owl::client_secret')
                )
        ));

    }

    private function getAccessToken($client_id, $client_secret)
    {
        // Check the Cache for an existing Access Token
        $cacheKey = 'owl-access-'.$client_id.'-'.$client_secret;

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        } else {
            $request = $this->client->get('/v1/oauth/token', array(), array(
                    'query' => array(
                        'grant_type' => 'client_credentials',
                        'client_id' => $client_id,
                        'client_secret' => $client_secret,
                    )
                ));
            $response = $request->send()->json();
            Cache::put($cacheKey, $response['access_token'], 24*60);
            return $response['access_token'];
        }
    }

    public function userRegisterProduct($email, $password, $firstName, $lastName, $duns, $productId, $acceptedTos, $source )
    {
        $request = $this->client->post('/v1.1/user/register-product', array(), array(
                        'email' => $email,
                        'password' => $password,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'duns' => $duns,
                        'product_id' => $productId,
                        'accepted_tos' => $acceptedTos,
                        'source' => $source
                    ));

        return $request->send()->json();

    }

    public function userLogin($email, $password)
    {
        $request = $this->client->post('/v1/user/token', array(), array(
                'email' => $email,
                'password' => $password
        ));

        return $request->send()->json();
    }

    public function userEntitlements($user_token)
    {

    }

}