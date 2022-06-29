<?php

namespace Cloudstek\DoctrineBehaviour\Tests\Subscriber;

use Cloudstek\DoctrineBehaviour\Listener\TimestampableListener;
use Cloudstek\DoctrineBehaviour\Subscriber\TimestampableSubscriber;
use Cloudstek\DoctrineBehaviour\Tests\Fixtures\ExpirableEntity;
use Cloudstek\DoctrineBehaviour\Tests\Fixtures\Timestampable\TimestampableEntity;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;

/**
 * @covers \Cloudstek\DoctrineBehaviour\Subscriber\TimestampableSubscriber
 */
class TimestampableSubscriberTest extends AbstractSubscriberTestCase
{
    public function testIsEventSubscriber(): void
    {
        $this->assertInstanceOf(EventSubscriber::class, new TimestampableSubscriber());
    }

    /**
     * @covers \Cloudstek\DoctrineBehaviour\Subscriber\TimestampableSubscriber::getSubscribedEvents
     */
    public function testSubscribedEvents(): void
    {
        $subscriber = new TimestampableSubscriber();

        $this->assertSame([Events::loadClassMetadata], $subscriber->getSubscribedEvents());
    }

    public function testLoadClassMetadata(): void
    {
        $em = $this->createEntityManager();
        $metadata = $em->getClassMetadata(TimestampableEntity::class);

        // Make sure we have no entity listeners.
        $this->assertNotNull($metadata);
        $this->assertEmpty($metadata->entityListeners);

        // Trigger loadClassMetadata event.
        $timestampableSubscriber = new TimestampableSubscriber();
        $timestampableSubscriber->loadClassMetadata(new LoadClassMetadataEventArgs($metadata, $em));

        // Check if we have any listeners
        $metadata = $em->getClassMetadata(TimestampableEntity::class);

        $this->assertNotNull($metadata);
        $this->assertEquals(
            [
                Events::prePersist => [
                    [
                        'class' => TimestampableListener::class,
                        'method' => Events::prePersist
                    ]
                ],
                Events::preUpdate => [
                    [
                        'class' => TimestampableListener::class,
                        'method' => Events::preUpdate
                    ]
                ]
            ],
            $metadata->entityListeners
        );
    }

    /**
     * @covers \Cloudstek\DoctrineBehaviour\Subscriber\TimestampableSubscriber::loadClassMetadata
     */
    public function testLoadClassMetadataSkipsIfListenerIsAlreadySet(): void
    {
        $em = $this->createEntityManager();
        $metadata = $em->getClassMetadata(TimestampableEntity::class);

        // Make sure we have no entity listeners.
        $this->assertNotNull($metadata);
        $this->assertEmpty($metadata->entityListeners);

        // Add existing entity listener
        $metadata->addEntityListener(
            Events::prePersist,
            TimestampableListener::class,
            Events::prePersist
        );

        // Make sure we have an existing entity listener.
        $metadata = $em->getClassMetadata(TimestampableEntity::class);

        $this->assertNotNull($metadata);
        $this->assertCount(1, $metadata->entityListeners);

        // Trigger loadClassMetadata event.
        $timestampableSubscriber = new TimestampableSubscriber();
        $timestampableSubscriber->loadClassMetadata(new LoadClassMetadataEventArgs($metadata, $em));

        // Make sure we only have the existing entity listener set.
        $metadata = $em->getClassMetadata(TimestampableEntity::class);

        $this->assertNotNull($metadata);
        $this->assertEquals(
            [
                Events::prePersist => [
                    [
                        'class' => TimestampableListener::class,
                        'method' => Events::prePersist
                    ]
                ]
            ],
            $metadata->entityListeners
        );
    }

    /**
     * @covers \Cloudstek\DoctrineBehaviour\Subscriber\TimestampableSubscriber::loadClassMetadata
     */
    public function testLoadClassMetadataOfNonTimestampableEntity(): void
    {
        $em = $this->createEntityManager();
        $metadata = $em->getClassMetadata(ExpirableEntity::class);

        // Save the list of configured entity listeners.
        $this->assertNotNull($metadata);

        $listeners = $metadata->entityListeners;

        // Trigger loadClassMetadata event.
        $timestampableSubscriber = new TimestampableSubscriber();
        $timestampableSubscriber->loadClassMetadata(new LoadClassMetadataEventArgs($metadata, $em));

        // Check if the entity listeners have been changed.
        $metadata = $em->getClassMetadata(ExpirableEntity::class);

        $this->assertNotNull($metadata);
        $this->assertEquals($listeners, $metadata->entityListeners);
    }
}
