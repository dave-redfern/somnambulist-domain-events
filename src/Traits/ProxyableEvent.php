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

namespace Somnambulist\DomainEvents\Traits;

use Somnambulist\DomainEvents\AbstractDomainEvent;

/**
 * Trait ProxyableEvent
 *
 * Provides the base Domain event mapping methods to allow the domain events
 * to be used with other EventDispatchers that have a hard-typed event interface
 * e.g. Doctrine requires EventArgs instances, Symfony requires Event etc.
 *
 * @package    Somnambulist\DomainEvents\Traits
 * @subpackage Somnambulist\DomainEvents\Traits\ProxyableEvent
 */
trait ProxyableEvent
{

    /**
     * @var AbstractDomainEvent
     */
    private $event;

    /**
     * Constructor.
     *
     * @param AbstractDomainEvent $event
     */
    public function __construct(AbstractDomainEvent $event)
    {
        $this->event = $event;
    }

    /**
     * @return AbstractDomainEvent
     */
    public function proxiedEvent()
    {
        return $this->event;
    }

    /**
     * @param string $name
     * @param array  $arguments
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->event, $name)) {
            return $this->event->$name(...$arguments);
        }

        throw new \BadMethodCallException(sprintf('Method "%s" does not exist on "%s"', $name, get_class($this->event)));
    }

    /**
     * @param AbstractDomainEvent $event
     *
     * @return static
     */
    public static function createFrom(AbstractDomainEvent $event)
    {
        return new static($event);
    }
}
