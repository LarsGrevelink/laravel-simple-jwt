<?php

namespace Tests\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Validator;
use LGrevelink\LaravelSimpleJWT\Providers\SimpleJwtServiceProvider;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use Tests\TestUtil;

class SimpleJwtServiceProviderTest extends TestCase
{
    /**
     * @var Application|MockObject
     */
    protected $app;

    /**
     * @var SimpleJwtServiceProvider
     */
    protected $serviceProvider;

    public function setUp(): void
    {
        parent::setUp();

        $this->app = $this->createMock(Application::class, ['version']);
        $this->serviceProvider = new SimpleJwtServiceProvider($this->app);
    }

    public function testIsLumenForLaravel()
    {
        $this->assertFalse(TestUtil::invokeMethod($this->serviceProvider, 'isLumen'));
    }

    public function testIsLumenForLumen()
    {
        $this->app->expects(self::any())->method('version')->willReturn('Lumen (99.99.99) (Laravel Components 99.99.*)');

        $this->assertTrue(TestUtil::invokeMethod($this->serviceProvider, 'isLumen'));
    }

    public function testSupportsFacadesForLaravel()
    {
        $this->app->expects(self::any())->method('version')->willReturn('99.99.99');

        $this->assertTrue(TestUtil::invokeMethod($this->serviceProvider, 'supportsFacades'));
    }

    public function testSupportsFacadesForLumen()
    {
        $this->app->expects(self::any())->method('version')->willReturn('Lumen (99.99.99) (Laravel Components 99.99.*)');

        $this->assertFalse(TestUtil::invokeMethod($this->serviceProvider, 'supportsFacades'));

        Validator::setFacadeApplication($this->app);

        $this->assertTrue(TestUtil::invokeMethod($this->serviceProvider, 'supportsFacades'));
    }
}
