<?php

namespace Cloudstek\DoctrineBehaviour\Tests\Fixtures\Translatable;

use Cloudstek\DoctrineBehaviour\Tests\Fixtures\AbstractEntity;
use Cloudstek\DoctrineBehaviour\TranslationInterface;
use Cloudstek\DoctrineBehaviour\TranslationTrait;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
class TranslatableEntityTranslation extends AbstractEntity implements TranslationInterface
{
    use TranslationTrait;

    public function __construct(
        ?string $locale = null,
        public ?string $name = null
    ) {
        if ($locale !== null) {
            $this->setLocale($locale);
        }
    }
}
