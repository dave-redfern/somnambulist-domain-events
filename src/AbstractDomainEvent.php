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

use Somnambulist\Collection\Immutable;
use Somnambulist\DomainEvents\Exceptions\InvalidPropertyException;
use Somnambulist\ValueObjects\Types\Identity\Aggregate;

/**
 * Class DomainEvent
 *
 * Based on the Gist by B. Eberlei https://gist.github.com/beberlei/53cd6580d87b1f5cd9ca
 *
 * @package    Somnambulist\DomainEvents\Events
 * @subpackage Somnambulist\DomainEvents\Events\DomainEvent
 * @author     Dave Redfern
 */
abstract class AbstractDomainEvent
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var Immutable
     */
    private $properties;

    /**
     * @var Immutable
     */
    private $context;

    /**
     * @var Aggregate
     */
    private $aggregate;

    /**
     * @var int
     */
    private $version;

    /**
     * @var float
     */
    private $time;



    /**
     * Constructor.
     *
     * @param array $payload Array of specific state change data
     * @param array $context Array of additional data providing context e.g. user, ip etc
     * @param int   $version A version identifier for the payload format
     */
    public function __construct(array $payload = [], array $context = [], $version = 1)
    {
        $this->properties = new Immutable($payload);
        $this->context    = new Immutable($context);
        $this->time       = microtime(true);
        $this->version    = $version;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->name();
    }

    /**
     * @param array     $payload
     * @param array     $context
     * @param int       $version
     *
     * @return static
     */
    public static function create(array $payload = [], array $context = [], $version = 1)
    {
        $event = new static($payload, $context, $version);

        return $event;
    }

    /**
     * @param Aggregate $aggregate
     * @param array     $payload
     * @param array     $context
     * @param int       $version
     *
     * @return static
     */
    public static function createFrom(Aggregate $aggregate, array $payload = [], array $context = [], $version = 1)
    {
        $event = static::create($payload, $context, $version);
        $event->setAggregate($aggregate);

        return $event;
    }

    /**
     * @return float
     */
    public function time(): float
    {
        return $this->time;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        if (is_null($this->name)) {
            $this->name = $this->parseName();
        }

        return $this->name;
    }

    /**
     * @return string
     */
    private function parseName(): string
    {
        $class = (new \ReflectionClass($this))->getShortName();

        if (substr($class, -5) === "Event") {
            $class = substr($class, 0, -5);
        }

        return $class;
    }

    /**
     * @return Immutable
     */
    public function properties()
    {
        return $this->properties;
    }

    /**
     * @return Immutable
     */
    public function context()
    {
        return $this->context;
    }

    /**
     * @return int
     */
    public function version(): int
    {
        return $this->version;
    }

    /**
     * @return Aggregate
     */
    public function aggregate()
    {
        return $this->aggregate;
    }



    /**
     * @param string $name
     *
     * @return mixed
     */
    public function property($name)
    {
        if (!$this->properties->has($name)) {
            throw InvalidPropertyException::propertyDoesNotExist($name);
        }

        return $this->properties->get($name);
    }

    /**
     * @param Aggregate $aggregate
     */
    public function setAggregate(Aggregate $aggregate)
    {
        if (is_null($this->aggregate)) {
            $this->aggregate = $aggregate;
        }
    }
}
