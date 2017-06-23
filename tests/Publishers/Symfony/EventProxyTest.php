<?php

namespace Somnambulist\Tests\DomainEvents\Publishers\Symfony;

use Somnambulist\DomainEvents\Publishers\Symfony\EventProxy;
use PHPUnit\Framework\TestCase;

/**
 * Class EventProxyTest
 *
 * @package    Somnambulist\Tests\DomainEvents\Publishers\Symfony
 * @subpackage Somnambulist\Tests\DomainEvents\Publishers\Symfony\EventProxyTest
 */
class EventProxyTest extends TestCase
{

    /**
     * @group publishers-symfony-proxy
     */
    public function testCreate()
    {
        $proxy = EventProxy::createFrom(new \MyEntityCreatedEvent(['id' => '1234', 'name' => 'name', 'another' => 'another']));

        $this->assertInstanceOf(\MyEntityCreatedEvent::class, $proxy->proxiedEvent());
        $this->assertEquals('1234', $proxy->property('id'));
        $this->assertEquals('my.entity.created', $proxy->name());
    }
}
