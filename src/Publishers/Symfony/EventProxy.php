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

namespace Somnambulist\DomainEvents\Publishers\Symfony;

use Somnambulist\Collection\Immutable;
use Somnambulist\DomainEvents\Traits\ProxyableEvent;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class EventProxy
 *
 * @package    Somnambulist\DomainEvents\Publishers\Symfony
 * @subpackage Somnambulist\DomainEvents\Publishers\Symfony\EventProxy
 *
 * @method Immutable context()
 * @method Immutable payload()
 * @method Immutable properties()
 * @method mixed property(string $name)
 * @method float time()
 * @method int version()
 */
class EventProxy extends Event
{

    use ProxyableEvent;

    /**
     * Returns a Symfony style dot separated name e.g.: OrderPlacedEvent -> order.placed
     *
     * @return string
     */
    public function name()
    {
        $value = $this->event->name();

        $value = preg_replace('/\s+/u', '', $value);
        $value = strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1.', $value));

        return $value;
    }
}
