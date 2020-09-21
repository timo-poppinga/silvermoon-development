<?php
declare(strict_types=1);

namespace SilvermoonTests\Injection;

use Silvermoon\Injection\Exception\ImplementationDoesNotExistsException;
use Silvermoon\Injection\Exception\InterfaceDoesNotExistsException;
use Silvermoon\Injection\Exception\WrongTypeException;
use Silvermoon\Injection\SimpleContainer;
use Silvermoon\TestingFramework\BaseUnitTest;
use SilvermoonTests\Injection\Fixtures\Display;
use SilvermoonTests\Injection\Fixtures\GreenDisplay;
use SilvermoonTests\Injection\Fixtures\Monitor;
use SilvermoonTests\Injection\Fixtures\Service\PlayGroundService;
use SilvermoonTests\Injection\Fixtures\Service\ScoreService;
use SilvermoonTests\Injection\Fixtures\Service\ScoreServiceInterface;
use SilvermoonTests\Injection\Fixtures\Service\ToolWithContainerService;

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


    /**
     * @return PlayGroundService
     * @throws ImplementationDoesNotExistsException
     * @throws WrongTypeException
     */
    public function testPsrGetClass()
    {
        /** @var PlayGroundService $playGroundService */
        $playGroundService = $this->simpleContainer->get(PlayGroundService::class);
        $this->assertInstanceOf(PlayGroundService::class, $playGroundService);
        return $playGroundService;
    }

    /**
     * @throws ImplementationDoesNotExistsException
     * @throws InterfaceDoesNotExistsException
     * @throws WrongTypeException
     */
    public function testPsrGetInterface()
    {
        $this->simpleContainer->register(ScoreServiceInterface::class, ScoreService::class);
        $scoreService = $this->simpleContainer->get(ScoreServiceInterface::class);
        $this->assertInstanceOf(ScoreService::class, $scoreService);
    }

    /**
     * @return PlayGroundService
     * @throws ImplementationDoesNotExistsException
     * @throws WrongTypeException
     */
    public function testGetNoInjection()
    {
        /** @var PlayGroundService $playGroundService */
        $playGroundService = $this->simpleContainer->getByClassName(PlayGroundService::class);
        $this->assertInstanceOf(PlayGroundService::class, $playGroundService);
        return $playGroundService;
    }

    /**
     * @throws ImplementationDoesNotExistsException
     * @throws WrongTypeException
     */
    public function testGetSingletonNoInjection()
    {
        /** @var PlayGroundService $playGroundService01 */
        $playGroundService01 = $this->simpleContainer->getByClassName(PlayGroundService::class);
        /** @var PlayGroundService $playGroundService02 */
        $playGroundService02 = $this->simpleContainer->getByClassName(PlayGroundService::class);

        $playGroundService01->count = 20;
        $this->assertSame($playGroundService01->count, $playGroundService02->count);
    }

    /**
     * @throws ImplementationDoesNotExistsException
     * @throws WrongTypeException
     */
    public function testGetNoSingletonNoInjection()
    {
        /** @var ScoreService $scoreService01 */
        $scoreService01 = $this->simpleContainer->getByClassName(ScoreService::class);
        /** @var ScoreService $scoreService02 */
        $scoreService02 = $this->simpleContainer->getByClassName(ScoreService::class);

        $scoreService01->count = 20;
        $this->assertNotEquals($scoreService01->count, $scoreService02->count);
    }

    /**
     * @throws ImplementationDoesNotExistsException
     * @throws InterfaceDoesNotExistsException
     * @throws WrongTypeException
     */
    public function testGetInjection()
    {
        $this->simpleContainer->register(ScoreServiceInterface::class, ScoreService::class);
        /** @var Display $display */
        $display = $this->simpleContainer->getByClassName(Display::class);

        $this->assertInstanceOf(Display::class, $display);
        $this->assertInstanceOf(ScoreService::class, $display->scoreService);
    }

    /**
     * @throws ImplementationDoesNotExistsException
     * @throws WrongTypeException
     */
    public function testGetInjectionNull()
    {
        /** @var Monitor $monitor */
        $monitor = $this->simpleContainer->getByClassName(Monitor::class);

        $this->assertInstanceOf(Monitor::class, $monitor);
        $this->assertNull($monitor->scoreService);
    }

    /**
     * @throws ImplementationDoesNotExistsException
     * @throws WrongTypeException
     */
    public function testClassDoesNotExistsException()
    {
        $this->expectException(ImplementationDoesNotExistsException::class);

        /** @var Display $display */
        $this->simpleContainer->getByClassName(Display::class . 'No');
    }

    /**
     * @throws ImplementationDoesNotExistsException
     * @throws InterfaceDoesNotExistsException
     * @throws WrongTypeException
     */
    public function testInjectionClass()
    {
        $this->simpleContainer->register(ScoreServiceInterface::class, ScoreService::class);
        /** @var GreenDisplay $greenDisplay */
        $greenDisplay = $this->simpleContainer->getByClassName(GreenDisplay::class);
        $this->assertInstanceOf(Display::class, $greenDisplay->display);
    }

    /**
     * @throws ImplementationDoesNotExistsException
     * @throws WrongTypeException
     */
    public function testInjectionOfContainer()
    {
        /** @var ToolWithContainerService $toolWithContainerService */
        $toolWithContainerService = $this->simpleContainer->getByClassName(ToolWithContainerService::class);
        $this->assertInstanceOf(SimpleContainer::class, $toolWithContainerService->container);
        $this->assertInstanceOf(SimpleContainer::class, $toolWithContainerService->psrContainer);
    }


    /**
     *
     */
    public function testGetClassNotExist(): void
    {
        self::expectException(ImplementationDoesNotExistsException::class);
        $this->simpleContainer->getByClassName(ToolWithContainerService::class . 'x');
    }
}
