<?php

namespace Cloudstek\DoctrineBehaviour\Tests\Fixtures;

use Cloudstek\DoctrineBehaviour\SoftDeletableInterface;
use Cloudstek\DoctrineBehaviour\SoftDeletableTrait;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
class SoftDeletableEntity extends AbstractEntity implements SoftDeletableInterface
{
    use SoftDeletableTrait;
}
