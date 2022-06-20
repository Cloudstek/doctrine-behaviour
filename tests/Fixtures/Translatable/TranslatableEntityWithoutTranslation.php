<?php

namespace Cloudstek\DoctrineBehaviour\Tests\Fixtures\Translatable;

use Cloudstek\DoctrineBehaviour\Tests\Fixtures\AbstractEntity;
use Cloudstek\DoctrineBehaviour\TranslatableInterface;
use Cloudstek\DoctrineBehaviour\TranslatableTrait;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
class TranslatableEntityWithoutTranslation extends AbstractEntity implements TranslatableInterface
{
    use TranslatableTrait;
}
