<?php

namespace Cloudstek\DoctrineBehaviour\Tests\Fixtures\Timestampable;

use Cloudstek\DoctrineBehaviour\Tests\Fixtures\AbstractEntity;
use Cloudstek\DoctrineBehaviour\TimestampableTrait;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
class TimestampableEntityWithoutInterfaces extends AbstractEntity
{
    use TimestampableTrait;
}
