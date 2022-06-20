<?php

namespace Cloudstek\DoctrineBehaviour\Tests\Fixtures\Translatable;

use Cloudstek\DoctrineBehaviour\Tests\Fixtures\AbstractEntity;
use Cloudstek\DoctrineBehaviour\TranslationInterface;
use Cloudstek\DoctrineBehaviour\TranslationTrait;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
class TranslationEntity extends AbstractEntity implements TranslationInterface
{
    use TranslationTrait;
}
