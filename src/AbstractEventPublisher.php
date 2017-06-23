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

namespace Somnambulist\DomainEvents;

use Somnambulist\Collection\Collection;
use Somnambulist\DomainEvents\Contracts\RaisesDomainEvents;

/**
 * Class AbstractEventPublisher
 *
 * @package    Somnambulist\DomainEvents
 * @subpackage Somnambulist\DomainEvents\AbstractEventPublisher
 */
abstract class AbstractEventPublisher
{

    /**
     * @var object An EventDispatcher instance
     */
    protected $dispatcher;

    /**
     * @var Collection|RaisesDomainEvents[]
     */
    private $entities;

    /**
     * Constructor.
     *
     * @param object $dispatcher
     */
    public function __construct($dispatcher)
    {
        $this->entities   = new Collection();
        $this->dispatcher = $dispatcher;
    }

    /**
     * Dispatch (publish) all domain events to the registered event dispatcher
     *
     * @return void
     */
    abstract public function dispatch();

    /**
     * Registers the entity for event broadcast
     *
     * @param RaisesDomainEvents $entity
     */
    public function publishEventsFrom(RaisesDomainEvents $entity)
    {
        $this->entities->add($entity);
    }

    /**
     * Removes the entity from the registry preventing events from being published
     *
     * @param RaisesDomainEvents $entity
     */
    public function stopPublishingEventsFrom(RaisesDomainEvents $entity)
    {
        $this->entities->removeElement($entity);
    }

    /**
     * Removes all entities from the internal registry
     */
    public function stopPublishingAllEvents()
    {
        $this->clear();
    }



    /**
     * @return Collection|RaisesDomainEvents[]
     */
    protected function entities()
    {
        return $this->entities;
    }

    /**
     * Gathers all
     *
     * @param Collection $entities
     *
     * @return Collection
     */
    protected function collateDomainEvents(Collection $entities)
    {
        $events = new Collection();

        $entities->each(function ($entity) use ($events) {
            /** @var RaisesDomainEvents $entity */
            $events->merge($entity->releaseAndResetEvents());

            return true;
        });

        return $events;
    }

    /**
     * @param Collection $events
     *
     * @return Collection
     */
    protected function orderDomainEvents(Collection $events)
    {
       return $events->sortUsing(function ($a, $b) {
            /** @var AbstractDomainEvent $a */
            /** @var AbstractDomainEvent $b */
            return bccomp($a->time(), $b->time(), 6);
        });
    }

    /**
     * Removes all record entities from the listener
     */
    protected function clear()
    {
        $this->entities->reset();
    }
}
