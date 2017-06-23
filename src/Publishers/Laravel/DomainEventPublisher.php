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

namespace Somnambulist\DomainEvents\Publishers\Laravel;

use Illuminate\Events\Dispatcher;
use Somnambulist\DomainEvents\AbstractEventPublisher;

/**
 * Class DomainEventPublisher
 *
 * @package    Somnambulist\DomainEvents\Publishers\Laravel
 * @subpackage Somnambulist\DomainEvents\Publishers\Laravel\DomainEventPublisher
 */
class DomainEventPublisher extends AbstractEventPublisher
{

    /**
     * Dispatch domain events via the Laravel event dispatcher
     */
    public function dispatch()
    {
        $events = $this->orderDomainEvents($this->collateDomainEvents($this->entities()));

        $events->each(function ($event) {
            $this->dispatcher()->dispatch($event);
        });
    }

    /**
     * @return Dispatcher
     */
    private function dispatcher()
    {
        return $this->dispatcher;
    }
}
