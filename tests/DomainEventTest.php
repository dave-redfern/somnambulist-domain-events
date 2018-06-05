<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace Somnambulist\Tests\DomainEvents;

use Events\NamespacedEvent;
use PHPUnit\Framework\TestCase;
use Somnambulist\Collection\Immutable;
use Somnambulist\DomainEvents\AbstractDomainEvent;
use Somnambulist\DomainEvents\Exceptions\InvalidPropertyException;
use Somnambulist\ValueObjects\Types\Identity\Aggregate;

/**
 * Class DomainEventTest
 *
 * @package    Somnambulist\Tests\DomainEvents
 * @subpackage Somnambulist\Tests\DomainEvents\DomainEventTest
 * @author     Dave Redfern
 */
class DomainEventTest extends TestCase
{

    /**
     * @group domain-event
     */
    public function testCanSetAggregateRoot()
    {
        $event = $this->getMockForAbstractClass(AbstractDomainEvent::class);
        $event->setAggregate(new Aggregate(\MyEntity::class, 1234));

        $this->assertEquals(\MyEntity::class, $event->aggregate()->class());
        $this->assertEquals(1234, $event->aggregate()->identity());
    }

    /**
     * @group domain-event
     */
    public function testCanGetEvetName()
    {
        $event = new NamespacedEvent();

        $this->assertEquals('Namespaced', $event->name());
    }

    /**
     * @group domain-event
     */
    public function testCanCastToString()
    {
        $event = new NamespacedEvent();

        $this->assertEquals('Namespaced', (string)$event);
    }

    /**
     * @group domain-event
     */
    public function testCreate()
    {
        $event = NamespacedEvent::create();

        $this->assertEquals('Namespaced', $event->name());
    }

    /**
     * @group domain-event
     */
    public function testCreateFrom()
    {
        $event = NamespacedEvent::createFrom(new Aggregate(__CLASS__, 'id'));

        $this->assertEquals('Namespaced', $event->name());
        $this->assertEquals(__CLASS__, $event->aggregate()->class());
        $this->assertEquals('id', $event->aggregate()->identity());
    }

    /**
     * @group domain-event
     */
    public function testCanUpdateContext()
    {
        $event = NamespacedEvent::create(['foo' => 'bar'], ['context' => 'value'], 2);

        $updated = $event->updateContext(['user' => 'user@example.example']);

        $this->assertEquals($event->time(), $updated->time());
        $this->assertEquals($event->version(), $updated->version());
        $this->assertEquals($event->properties()->toArray(), $updated->properties()->toArray());
        $this->assertEquals(['context' => 'value', 'user' => 'user@example.example'], $updated->context()->toArray());
    }

    /**
     * @group domain-event
     */
    public function testCanGetVersion()
    {
        $event = $this->getMockForAbstractClass(AbstractDomainEvent::class);

        $this->assertEquals(1, $event->version());
    }

    /**
     * @group domain-event
     */
    public function testCanGetContext()
    {
        $event = $this->getMockForAbstractClass(AbstractDomainEvent::class);

        $this->assertInstanceOf(Immutable::class, $event->context());
    }

    /**
     * @group domain-event
     */
    public function testCanGetProperties()
    {
        $event = $this->getMockForAbstractClass(AbstractDomainEvent::class);

        $this->assertInstanceOf(Immutable::class, $event->properties());
    }

    /**
     * @group domain-event
     */
    public function testCanGetProperty()
    {
        $event = $this->getMockForAbstractClass(AbstractDomainEvent::class, [
            [
                'foo' => 'bar',
            ]
        ]);

        $this->assertEquals('bar', $event->property('foo'));
    }

    /**
     * @group domain-event
     */
    public function testGetPropertyRaisesExceptionIfNotFound()
    {
        $event = $this->getMockForAbstractClass(AbstractDomainEvent::class, [
            [
                'foo' => 'bar',
            ]
        ]);

        $this->expectException(InvalidPropertyException::class);
        $event->property('baz');
    }
}
