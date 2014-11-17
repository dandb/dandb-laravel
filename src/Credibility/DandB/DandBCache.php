<?php namespace Credibility\DandB;

use Credibility\DandB\Cache\CacheableInterface;

class DandBCache implements CacheableInterface {

    protected $cache;

    public function __construct($cache)
    {
        $this->cache = $cache;
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->cache->get($key);
    }

    /**
     * Returns whether an item exists or not
     *
     * @param $key
     * @return boolean
     */
    public function has($key)
    {
        return $this->cache->has($key);
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param  string $key
     * @param  mixed $value
     * @param  int $minutes
     * @return void
     */
    public function put($key, $value, $minutes)
    {
        $this->cache->put($key, $value, $minutes);
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function increment($key, $value = 1)
    {
        $this->cache->increment($key, $value);
    }

    /**
     * Decrement the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function decrement($key, $value = 1)
    {
        $this->cache->decrement($key, $value);
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function forever($key, $value)
    {
        $this->cache->forever($key, $value);
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string $key
     * @return void
     */
    public function forget($key)
    {
        $this->cache->forget($key);
    }

    /**
     * Remove all items from the cache.
     *
     * @return void
     */
    public function flush()
    {
        $this->cache->flush();
    }

    /**
     * Get the cache key prefix.
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->cache->getPrefix();
    }
}