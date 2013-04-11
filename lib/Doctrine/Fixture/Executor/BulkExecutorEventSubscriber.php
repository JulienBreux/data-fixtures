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

namespace Doctrine\Fixture\Executor;

use Doctrine\Common\EventSubscriber;
use Doctrine\Fixture\Event\FixtureEvent;
use Doctrine\Fixture\Event\BulkFixtureEvent;
use Doctrine\Fixture\Event\BulkImportFixtureEventListener;
use Doctrine\Fixture\Event\BulkPurgeFixtureEventListener;
use Doctrine\Fixture\Event\ImportFixtureEventListener;
use Doctrine\Fixture\Event\PurgeFixtureEventListener;

/**
 * Bulk Executor Event Subscriber.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class BulkExecutorEventSubscriber implements
    EventSubscriber,
    BulkImportFixtureEventListener,
    BulkPurgeFixtureEventListener
{
    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            BulkImportFixtureEventListener::BULK_IMPORT,
            BulkPurgeFixtureEventListener::BULK_PURGE,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function bulkPurge(BulkFixtureEvent $event)
    {
        $eventManager = $event->getConfiguration()->getEventManager();

        foreach ($event->getFixtureList() as $fixture) {
            $eventManager->dispatchEvent(PurgeFixtureEventListener::PURGE, new FixtureEvent($fixture));

            $fixture->purge();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function bulkImport(BulkFixtureEvent $event)
    {
        $eventManager = $event->getConfiguration()->getEventManager();

        foreach ($event->getFixtureList() as $fixture) {
            $eventManager->dispatchEvent(ImportFixtureEventListener::IMPORT, new FixtureEvent($fixture));

            $fixture->import();
        }
    }
}
