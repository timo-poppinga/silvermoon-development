<?php
declare(strict_types=1);

namespace SilvermoonTests\Injection;

use Silvermoon\Injection\SimpleContainer;
use Silvermoon\TestingFramework\BaseUnitTest;
use SilvermoonTests\Injection\Fixtures\Service\PlayGroundService;
use SilvermoonTests\Injection\Fixtures\Service\ScoreService;

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
     * @param PlayGroundService $playGroundService
     * @throws \Silvermoon\Injection\Exception\ClassDoesNotExistsException
     * @throws \Silvermoon\Injection\Exception\ImplementationDoesNotExistsException
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
     * @param PlayGroundService $playGroundService
     * @throws \Silvermoon\Injection\Exception\ClassDoesNotExistsException
     * @throws \Silvermoon\Injection\Exception\ImplementationDoesNotExistsException
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
}
