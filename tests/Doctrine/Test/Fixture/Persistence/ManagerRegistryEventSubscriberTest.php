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
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\Test\Fixture\Persistence;

use Doctrine\Fixture\Persistence\ManagerRegistryEventSubscriber;
use Doctrine\Fixture\Event\FixtureEvent;
use Doctrine\Fixture\Event\ImportFixtureEventListener;
use Doctrine\Fixture\Event\PurgeFixtureEventListener;
use Doctrine\Test\Mock\Persistence\ManagerRegistry;

/**
 * ManagerRegistryEventSubscriber tests.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class ManagerRegistryEventSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\Common\Persistence\ManagerRegistry
     */
    private $registry;

    /**
     * @var \Doctrine\Fixture\Persistence\ManagerRegistryEventSubscriber
     */
    private $subscriber;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->registry   = new ManagerRegistry();
        $this->subscriber = new ManagerRegistryEventSubscriber($this->registry);
    }

    public function testGetSubscribedEvents()
    {
        $subscribedEventList = $this->subscriber->getSubscribedEvents();

        $this->assertContains(ImportFixtureEventListener::IMPORT, $subscribedEventList);
        $this->assertContains(PurgeFixtureEventListener::PURGE, $subscribedEventList);
    }

    public function testImport()
    {
        $mockFixture = $this->getMockBuilder('Doctrine\Test\Mock\Unassigned\FixtureA')
            ->disableOriginalConstructor()
            ->getMock();

        $mockFixture->expects($this->once())
                 ->method('setManagerRegistry')
                 ->with($this->equalTo($this->registry));

        $event = new FixtureEvent($mockFixture);

        $this->subscriber->import($event);
    }

    public function testImportNoInterface()
    {
        $mockFixture = $this->getMockBuilder('Doctrine\Test\Mock\Unassigned\FixtureB')
            ->disableOriginalConstructor()
            ->getMock();

        $mockFixture->expects($this->never())
                 ->method('setManagerRegistry');

        $event = new FixtureEvent($mockFixture);

        $this->subscriber->import($event);
    }

    public function testPurge()
    {
        $mockFixture = $this->getMockBuilder('Doctrine\Test\Mock\Unassigned\FixtureA')
            ->disableOriginalConstructor()
            ->getMock();

        $mockFixture->expects($this->once())
                 ->method('setManagerRegistry')
                 ->with($this->equalTo($this->registry));

        $event = new FixtureEvent($mockFixture);

        $this->subscriber->purge($event);
    }

    public function testPurgeNoInterface()
    {
        $mockFixture = $this->getMockBuilder('Doctrine\Test\Mock\Unassigned\FixtureB')
            ->disableOriginalConstructor()
            ->getMock();

        $mockFixture->expects($this->never())
                 ->method('setManagerRegistry');

        $event = new FixtureEvent($mockFixture);

        $this->subscriber->purge($event);
    }

}