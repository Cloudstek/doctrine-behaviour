<?php

namespace Cloudstek\DoctrineBehaviour\Tests\Fixtures\Translatable;

use Cloudstek\DoctrineBehaviour\Tests\Fixtures\AbstractEntity;
use Cloudstek\DoctrineBehaviour\TranslatableInterface;
use Cloudstek\DoctrineBehaviour\TranslatableTrait;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
class TranslatableEntity extends AbstractEntity implements TranslatableInterface
{
    use TranslatableTrait;

    public function __construct(iterable $translations = [])
    {
        $this->initTranslations($translations);
    }

    public function __clone(): void
    {
        $this->cloneTranslations();
    }
}
