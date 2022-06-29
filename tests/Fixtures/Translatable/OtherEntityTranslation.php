<?php

namespace Cloudstek\DoctrineBehaviour\Tests\Fixtures\Translatable;

use Cloudstek\DoctrineBehaviour\Tests\Fixtures\AbstractEntity;
use Cloudstek\DoctrineBehaviour\TranslationInterface;
use Cloudstek\DoctrineBehaviour\TranslationTrait;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
class OtherEntityTranslation extends AbstractEntity implements TranslationInterface
{
    use TranslationTrait;

    public function __construct(
        ?string $locale = null
    ) {
        if ($locale !== null) {
            $this->setLocale($locale);
        }
    }
}
