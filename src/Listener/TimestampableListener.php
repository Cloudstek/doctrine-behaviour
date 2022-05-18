<?php

namespace Cloudstek\DoctrineBehaviour\Listener;

use Carbon\CarbonImmutable;
use Cloudstek\DoctrineBehaviour\TimestampableInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class TimestampableListener
{
    /**
     * Handle prePersist event.
     *
     * @param object             $entity
     * @param LifecycleEventArgs $event
     */
    public function prePersist(object $entity, LifecycleEventArgs $event): void
    {
        $now = CarbonImmutable::now('UTC');

        if (!$entity instanceof TimestampableInterface) {
            return;
        }

        if ($entity->getCreatedAt() === null) {
            $entity->setCreatedAt($now);
        }

        if ($entity->getUpdatedAt() === null) {
            $entity->setUpdatedAt($now);
        }
    }

    /**
     * Handle preUpdate event.
     *
     * @param object             $entity
     * @param PreUpdateEventArgs $event
     */
    public function preUpdate(object $entity, PreUpdateEventArgs $event): void
    {
        if (!$entity instanceof TimestampableInterface) {
            return;
        }

        $entity->setUpdatedAt(CarbonImmutable::now('UTC'));
    }
}
