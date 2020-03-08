<?php
declare(strict_types=1);

namespace SilvermoonTests\Injection;

use Silvermoon\Injection\Exception\ClassDoesNotExistsException;
use Silvermoon\Injection\SimpleContainer;
use Silvermoon\TestingFramework\BaseUnitTest;
use SilvermoonTests\Injection\Fixtures\Display;
use SilvermoonTests\Injection\Fixtures\Monitor;
use SilvermoonTests\Injection\Fixtures\Service\PlayGroundService;
use SilvermoonTests\Injection\Fixtures\Service\ScoreService;
use SilvermoonTests\Injection\Fixtures\Service\ScoreServiceInterface;

class SimpleContainerTest extends BaseUnitTest
{
    /**
     * @var SimpleContainer
     */
    protected SimpleContainer $simpleContainer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->simpleContainer = new SimpleContainer();
    }

    public function testGetNoInjection()
    {
        /** @var PlayGroundService $playGroundService */
        $playGroundService = $this->simpleContainer->get(PlayGroundService::class);
        $this->assertInstanceOf(PlayGroundService::class, $playGroundService);
        return $playGroundService;
    }

    /**
     * @throws ClassDoesNotExistsException
     * @throws \Silvermoon\Injection\Exception\ImplementationDoesNotExistsException
     * @throws \Silvermoon\Injection\Exception\WrongTypeException
     */
    public function testGetSingletonNoInjection()
    {
        /** @var PlayGroundService $playGroundService01 */
        $playGroundService01 = $this->simpleContainer->get(PlayGroundService::class);
        /** @var PlayGroundService $playGroundService02 */
        $playGroundService02 = $this->simpleContainer->get(PlayGroundService::class);

        $playGroundService01->count = 20;
        $this->assertSame($playGroundService01->count, $playGroundService02->count);
    }

    /**
     * @throws ClassDoesNotExistsException
     * @throws \Silvermoon\Injection\Exception\ImplementationDoesNotExistsException
     * @throws \Silvermoon\Injection\Exception\WrongTypeException
     */
    public function testGetNoSingletonNoInjection()
    {
        /** @var ScoreService $scoreService01 */
        $scoreService01 = $this->simpleContainer->get(ScoreService::class);
        /** @var ScoreService $scoreService02 */
        $scoreService02 = $this->simpleContainer->get(ScoreService::class);

        $scoreService01->count = 20;
        $this->assertNotEquals($scoreService01->count, $scoreService02->count);
    }

    /**
     * @throws \Silvermoon\Injection\Exception\ClassDoesNotExistsException
     * @throws \Silvermoon\Injection\Exception\ImplementationDoesNotExistsException
     * @throws \Silvermoon\Injection\Exception\InterfaceDoesNotExistsException
     * @throws \Silvermoon\Injection\Exception\WrongTypeException
     */
    public function testGetInjection()
    {
        $this->simpleContainer->register(ScoreServiceInterface::class, ScoreService::class);
        /** @var Display $display */
        $display = $this->simpleContainer->get(Display::class);

        $this->assertInstanceOf(Display::class, $display);
        $this->assertInstanceOf(ScoreService::class, $display->scoreService);
    }

    /**
     * @throws ClassDoesNotExistsException
     * @throws \Silvermoon\Injection\Exception\ImplementationDoesNotExistsException
     * @throws \Silvermoon\Injection\Exception\WrongTypeException
     */
    public function testGetInjectionNull()
    {
        /** @var Monitor $monitor */
        $monitor = $this->simpleContainer->get(Monitor::class);

        $this->assertInstanceOf(Monitor::class, $monitor);
        $this->assertNull($monitor->scoreService);
    }

    /**
     * @throws ClassDoesNotExistsException
     * @throws \Silvermoon\Injection\Exception\ImplementationDoesNotExistsException
     * @throws \Silvermoon\Injection\Exception\WrongTypeException
     */
    public function testClassDoesNotExistsException()
    {
        $this->expectException(ClassDoesNotExistsException::class);

        /** @var Display $display */
        $this->simpleContainer->get(Display::class . 'No');
    }
}
