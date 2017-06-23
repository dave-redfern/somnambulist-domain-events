<?php

namespace Somnambulist\Tests\DomainEvents\Dispatchers\Laravel;

use Carbon\Carbon;
use Illuminate\Events\Dispatcher;
use PHPUnit\Framework\TestCase;
use Somnambulist\DomainEvents\Publishers\Laravel\DomainEventPublisher;

/**
 * Class DomainEventPublisherTest
 *
 * @package    Somnambulist\Tests\DomainEvents\Publishers\Laravel
 * @subpackage Somnambulist\Tests\DomainEvents\Publishers\Laravel\DomainEventPublisherTest
 */
class DomainEventPublisherTest extends TestCase
{

    /**
     * @var DomainEventPublisher
     */
    private $publisher;

    protected function setUp()
    {
        $listener   = new \EventListener();
        $dispatcher = new Dispatcher();
        $dispatcher->listen(\MyEntityCreatedEvent::class, [$listener, 'onMyEntityCreated']);
        $dispatcher->listen(\MyEntityAddedAnotherEntity::class, [$listener, 'onMyEntityAddedAnotherEntity']);

        $this->publisher = new DomainEventPublisher($dispatcher);
    }

    protected function tearDown()
    {
        $this->publisher = null;
    }

    /**
     * @group dispatchers-symfony-dispatcher
     */
    public function testPublishesDomainEvents()
    {
        $entity = new \MyEntity('test-id', 'test', 'bob', Carbon::now());

        $this->publisher->publishEventsFrom($entity);

        $this->expectOutputString("New item created with id: test-id, name: test, another: bob\n");

        $this->publisher->dispatch();

        $this->assertCount(0, $entity->releaseAndResetEvents());
    }

    /**
     * @group dispatchers-symfony-dispatcher
     */
    public function testFiresEventsWhenRelatedEntitiesChangedButRootNot()
    {
        $entity = new \MyEntity('test-id', 'test', 'bob', Carbon::now());

        $this->publisher->publishEventsFrom($entity);
        $this->publisher->dispatch();

        $this->assertCount(0, $entity->releaseAndResetEvents());

        $this->getActualOutput();

        sleep(1);

        $entity->addRelated('example', 'test-test', Carbon::now());

        $this->publisher->dispatch();

        $expected  = "New item created with id: test-id, name: test, another: bob\n";
        $expected .= "Added related entity with name: example, another: test-test to entity id: test-id\n";

        $this->expectOutputString($expected);

        $this->assertCount(0, $entity->releaseAndResetEvents());
    }

    /**
     * @group dispatchers-symfony-dispatcher
     */
    public function testFiresEventsInOrder()
    {
        $entity = new \MyEntity('test-id', 'test', 'bob', Carbon::now());

        $entity->addRelated('example1', 'test-test1', Carbon::now());
        $entity->addRelated('example2', 'test-test2', Carbon::now());
        $entity->addRelated('example3', 'test-test3', Carbon::now());

        $this->publisher->publishEventsFrom($entity);
        $this->publisher->dispatch();

        $expected  = "New item created with id: test-id, name: test, another: bob\n";
        $expected .= "Added related entity with name: example1, another: test-test1 to entity id: test-id\n";
        $expected .= "Added related entity with name: example2, another: test-test2 to entity id: test-id\n";
        $expected .= "Added related entity with name: example3, another: test-test3 to entity id: test-id\n";

        $this->expectOutputString($expected);

        $this->assertCount(0, $entity->releaseAndResetEvents());
    }

    /**
     * @group dispatchers-symfony-dispatcher
     */
    public function testCanStopListeningToEvents()
    {
        $entity = new \MyEntity('test-id', 'test', 'bob', Carbon::now());

        $this->publisher->publishEventsFrom($entity);
        $this->publisher->dispatch();

        $this->getActualOutput();

        $entity->addRelated('example1', 'test-test1', Carbon::now());
        $entity->addRelated('example2', 'test-test2', Carbon::now());
        $entity->addRelated('example3', 'test-test3', Carbon::now());

        $this->publisher->stopPublishingEventsFrom($entity);
        $this->publisher->dispatch();

        // appears to be no means to clear the output?
        $expected = "New item created with id: test-id, name: test, another: bob\n";

        $this->expectOutputString($expected);

        $this->assertCount(3, $entity->releaseAndResetEvents());
    }

    /**
     * @group dispatchers-symfony-dispatcher
     */
    public function testCanStopListeningToAllEvents()
    {
        $entity = new \MyEntity('test-id', 'test', 'bob', Carbon::now());

        $this->publisher->publishEventsFrom($entity);
        $this->publisher->dispatch();

        $this->getActualOutput();

        $entity->addRelated('example1', 'test-test1', Carbon::now());
        $entity->addRelated('example2', 'test-test2', Carbon::now());
        $entity->addRelated('example3', 'test-test3', Carbon::now());

        $this->publisher->stopPublishingAllEvents();
        $this->publisher->dispatch();

        // appears to be no means to clear the output?
        $expected = "New item created with id: test-id, name: test, another: bob\n";

        $this->expectOutputString($expected);

        $this->assertCount(3, $entity->releaseAndResetEvents());
    }
}