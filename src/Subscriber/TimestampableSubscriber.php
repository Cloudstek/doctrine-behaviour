<?php

declare(strict_types=1);

namespace Cloudstek\DoctrineBehaviour\Subscriber;

use Cloudstek\DoctrineBehaviour\Listener\TimestampableListener;
use Cloudstek\DoctrineBehaviour\TimestampableInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\MappingException;

/**
 * Timestampable event subscriber.
 *
 * This event subscriber automatically adds the timestampable listeners to each entity's class metadata that implements
 * the timestampable interface.
 *
 * @see https://www.doctrine-project.org/projects/doctrine-orm/en/2.11/reference/events.html
 */
class TimestampableSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::loadClassMetadata
        ];
    }

    /**
     * @throws MappingException
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $classMetadata = $eventArgs->getClassMetadata();

        if ($classMetadata->reflClass->implementsInterface(TimestampableInterface::class) === false) {
            return;
        }

        $entityListeners = $classMetadata->entityListeners;

        // Return if entity listener has already been configured.
        foreach ($entityListeners as $listeners) {
            foreach ($listeners as $listener) {
                if ($listener['class'] === TimestampableListener::class) {
                    return;
                }
            }
        }

        $classMetadata->addEntityListener(
            Events::prePersist,
            TimestampableListener::class,
            'prePersist'
        );

        $classMetadata->addEntityListener(
            Events::preUpdate,
            TimestampableListener::class,
            'preUpdate'
        );
    }
}
