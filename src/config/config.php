<?php

return array(

    /**
     * Base URL for requests
     */
    'base_url' => '',

    /**
     * Client ID for OAuth Authentication
     */
    'client_id' => '',

    /**
     * Client Secret for OAuth Authentication
     */
    'client_secret' => '',

    /**
     * Default Cache TTL for Access Tokens
     */
    'cache_ttl' => 60,

    /**
     * Options for GuzzleClient
     * @see http://guzzle.readthedocs.org/en/latest/clients.html#request-options
     * Note: exact names required here
     */
    'options' => array(

        /** Request Timeout */
        'timeout' => 3,

        /** Connection Timeout */
        'connect_timeout' => 3,

    )

);