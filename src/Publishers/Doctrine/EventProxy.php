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

namespace Somnambulist\DomainEvents\Publishers\Doctrine;

use Doctrine\Common\EventArgs;
use Somnambulist\Collection\Immutable;
use Somnambulist\DomainEvents\Traits\ProxyableEvent;

/**
 * Class EventProxy
 *
 * @package    Somnambulist\DomainEvents\Publishers\Doctrine
 * @subpackage Somnambulist\DomainEvents\Publishers\Doctrine\EventProxy
 *
 * @method string name()
 * @method Immutable context()
 * @method Immutable payload()
 * @method Immutable properties()
 * @method mixed property(string $name)
 * @method float time()
 * @method int version()
 */
class EventProxy extends EventArgs
{

    use ProxyableEvent;

}
