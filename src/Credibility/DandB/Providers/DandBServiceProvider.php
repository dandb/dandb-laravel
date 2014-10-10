<?php namespace Credibility\DandB\Providers;

use Credibility\DandB\ClientFactory;
use Credibility\DandB\DandB;
use Credibility\DandB\DandBLaravel;
use Credibility\DandB\Requester;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class DandBServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
        $namespace = 'dandb-laravel';
        $path = __DIR__ . '/../../..';
		$this->package('credibility/dandb-laravel', $namespace, $path);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->bind('dandb', function($app) {
            $clientFactory = new ClientFactory($this->getBaseUrl(), $this->getGuzzleOpts());
            list($clientId, $clientSecret) = $this->getClientIdAndSecret();
            $requester = new Requester($clientFactory, $clientId, $clientSecret);
            $dandb = new DandB($requester);

            return new DandBLaravel($dandb, $app);
        });
	}

    public function getBaseUrl()
    {
        return $this->app->make('config')->get('dandb-laravel::base_url');
    }

    public function getGuzzleOpts()
    {
        return $this->app->make('config')->get('dandb-laravel::options');
    }

    public function getClientIdAndSecret()
    {
        $clientId = $this->app->make('config')->get('dandb-laravel::client_id');
        $clientSecret = $this->app->make('config')->get('dandb-laravel::client_secret');

        return array($clientId, $clientSecret);
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('dandb');
	}

}