<?php

namespace Cloudstek\DoctrineBehaviour\Tests\Fixtures;

use Cloudstek\DoctrineBehaviour\ExpirableInterface;
use Cloudstek\DoctrineBehaviour\ExpirableTrait;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
class ExpirableEntity extends AbstractEntity implements ExpirableInterface
{
    use ExpirableTrait;
}
