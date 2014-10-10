<?php namespace Credibility\DandB;

use Mockery\MockInterface;
use PHPUnit_Framework_TestCase;
use Mockery as m;

class TestCase extends PHPUnit_Framework_TestCase {

    protected $clientId = 'test-client';
    protected $clientSecret = 'test-secret';
    protected $baseUrl = 'https://api-qa.malibucoding.com';
    protected $cacheTTL = 60;

    protected $environment = 'testing';

    /** @var MockInterface */
    protected $mockCache;

    /** @var MockInterface */
    protected $mockApp;

    public function setUp()
    {
        $mockConfig = m::mock('Config');

        $mockConfig->shouldReceive('get')
            ->with('dandb-laravel::client_id')
            ->andReturn($this->clientId);

        $mockConfig->shouldReceive('get')
            ->with('dandb-laravel::client_secret')
            ->andReturn($this->clientSecret);

        $mockConfig->shouldReceive('get')
            ->with('dandb-laravel::base_url')
            ->andReturn($this->baseUrl);

        $mockConfig->shouldReceive('get')
            ->with('dandb-laravel::cache_ttl')
            ->andReturn($this->cacheTTL);

        $this->mockCache = m::mock('Cache');

        $this->mockApp = m::mock('Illuminate\Foundation\Application');
        $this->mockApp->shouldReceive('make')
            ->with('config')
            ->andReturn($mockConfig);

        $this->mockApp->shouldReceive('make')
            ->with('cache')
            ->andReturn($this->mockCache);
    }

} 