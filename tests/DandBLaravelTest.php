<?php namespace Credibility\DandB;

use Exception;
use Mockery as m;
use Mockery\MockInterface;

class DandBLaravelTest extends TestCase {

    /** @var MockInterface */
    protected $mockDandb;

    /** @var MockInterface */
    protected $mockAccessToken;

    /** @var MockInterface */
    protected $mockDandbResponse;

    /** @var DandBLaravel */
    protected $dandb;

    public function setUp()
    {
        parent::setUp();
        $this->mockDandb = m::mock('Credibility\DandB\DandB');
        $this->mockAccessToken = 'test-access-token';
        $this->mockDandbResponse = m::mock('Credibility\DandB\Response');

        $this->dandb = new DandBLaravel($this->mockDandb, $this->mockApp);
    }

    public function testGetAccessTokenCacheKey()
    {
        $key = $this->dandb->getAccessTokenCacheKey();

        $this->assertEquals('owl_access_test-client_test-secret', $key);
    }

    public function testCallWithAccessToken()
    {
        $key = $this->dandb->getAccessTokenCacheKey();

        $this->mockCache->shouldReceive('has')
            ->with($key)
            ->andReturn(true);

        $this->mockCache->shouldReceive('get')
            ->with($key)
            ->andReturn($this->mockAccessToken);

        $this->mockDandb->shouldReceive('internationalSearchByDuns')
            ->with('test-duns', $this->mockAccessToken)
            ->andReturn($this->mockDandbResponse);

        $response = $this->dandb->internationalSearchByDuns('test-duns');

        $this->assertTrue($response == $this->mockDandbResponse);
    }

    public function testCallWithoutAccessToken()
    {
        $key = $this->dandb->getAccessTokenCacheKey();

        $this->mockCache->shouldReceive('has')
            ->with($key)
            ->andReturn(false);

        $this->mockDandb->shouldReceive('getAccessToken')
            ->andReturn($this->mockAccessToken);

        $this->mockCache->shouldReceive('put')
            ->with($key, $this->mockAccessToken, $this->cacheTTL)
            ->once();

        $this->mockDandb->shouldReceive('internationalSearchByDuns')
            ->with('test-duns', $this->mockAccessToken)
            ->andReturn($this->mockDandbResponse);

        $response = $this->dandb->internationalSearchByDuns('test-duns');

        $this->assertTrue($response == $this->mockDandbResponse);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Function does not exist in DandB
     */
    public function testCallWithInvalidMethod()
    {
        $this->dandb->methodDoesNotExist();
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage GET Access Token failed
     */
    public function testAccessTokenExceptionThrown()
    {
        $key = $this->dandb->getAccessTokenCacheKey();

        $this->mockCache->shouldReceive('has')
            ->with($key)
            ->andReturn(false);

        $this->mockDandb->shouldReceive('getAccessToken')
            ->andReturn(false);

        $this->dandb->internationalSearchByDuns('test-duns');
    }


}
 