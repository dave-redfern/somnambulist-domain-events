<?php

namespace Somnambulist\Tests\DomainEvents\Dispatchers\Doctrine;

use Somnambulist\DomainEvents\Publishers\Doctrine\EventProxy;
use PHPUnit\Framework\TestCase;

/**
 * Class EventProxyTest
 *
 * @package    Somnambulist\Tests\DomainEvents\Publishers\Doctrine
 * @subpackage Somnambulist\Tests\DomainEvents\Publishers\Doctrine\EventProxyTest
 */
class EventProxyTest extends TestCase
{

    /**
     * @group dispatchers-doctrine-proxy
     */
    public function testCreate()
    {
        $proxy = EventProxy::createFrom(new \MyEntityCreatedEvent(['id' => '1234', 'name' => 'name', 'another' => 'another']));

        $this->assertInstanceOf(\MyEntityCreatedEvent::class, $proxy->proxiedEvent());
        $this->assertEquals('1234', $proxy->property('id'));
        $this->assertEquals('MyEntityCreated', $proxy->name());
    }

    /**
     * @group dispatchers-doctrine-proxy
     */
    public function testInvalidMethodRaisesException()
    {
        $proxy = EventProxy::createFrom(new \MyEntityCreatedEvent(['id' => '1234', 'name' => 'name', 'another' => 'another']));

        $this->expectException(\BadMethodCallException::class);
        $proxy->bob();
    }
}
