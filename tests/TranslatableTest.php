<?php

namespace Cloudstek\DoctrineBehaviour\Tests;

use Cloudstek\DoctrineBehaviour\Exception\TranslationNotFoundException;
use Cloudstek\DoctrineBehaviour\Tests\Fixtures\Translatable\OtherEntityTranslation;
use Cloudstek\DoctrineBehaviour\Tests\Fixtures\Translatable\TranslatableEntity;
use Cloudstek\DoctrineBehaviour\Tests\Fixtures\Translatable\TranslatableEntityTranslation;
use Cloudstek\DoctrineBehaviour\TranslatableTrait;
use Cloudstek\DoctrineBehaviour\TranslationInterface;
use Cloudstek\DoctrineBehaviour\TranslationTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(TranslatableTrait::class)]
#[CoversClass(TranslationTrait::class)]
class TranslatableTest extends TestCase
{
    public function testCanInitialiseEmpty(): void
    {
        $entity = new TranslatableEntity();

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertTrue($translations->isEmpty());
    }

    public function testCanInitialiseWithArray(): void
    {
        $englishTranslation = new TranslatableEntityTranslation('en');
        $germanTranslation = new TranslatableEntityTranslation('de');

        $entity = new TranslatableEntity([$englishTranslation, $germanTranslation]);

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(2, $translations);

        $this->assertTrue($translations->contains($englishTranslation));
        $this->assertSame($entity, $englishTranslation->getTranslatable());
        $this->assertArrayHasKey('en', $translations);

        $this->assertTrue($translations->contains($germanTranslation));
        $this->assertSame($entity, $germanTranslation->getTranslatable());
        $this->assertArrayHasKey('de', $translations);
    }

    public function testCanInitialiseWithArrayCollection(): void
    {
        $englishTranslation = new TranslatableEntityTranslation('en');
        $germanTranslation = new TranslatableEntityTranslation('de');

        $entity = new TranslatableEntity(
            new ArrayCollection([$englishTranslation, $germanTranslation])
        );

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(2, $translations);

        $this->assertTrue($translations->contains($englishTranslation));
        $this->assertSame($entity, $englishTranslation->getTranslatable());
        $this->assertArrayHasKey('en', $translations);

        $this->assertTrue($translations->contains($germanTranslation));
        $this->assertSame($entity, $germanTranslation->getTranslatable());
        $this->assertArrayHasKey('de', $translations);
    }

    public function testCanInitialiseWithArrayContainingDuplicates(): void
    {
        $englishTranslation1 = new TranslatableEntityTranslation('en');
        $englishTranslation2 = new TranslatableEntityTranslation('en');
        $germanTranslation = new TranslatableEntityTranslation('de');

        $entity = new TranslatableEntity(
            [
                $englishTranslation1,
                $englishTranslation2,
                $germanTranslation
            ]
        );

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(2, $translations);

        $this->assertFalse($translations->contains($englishTranslation1));
        $this->assertArrayHasKey('en', $translations);
        $this->assertNotSame($translations['en'], $englishTranslation1);

        $this->assertTrue($translations->contains($englishTranslation2));
        $this->assertSame($entity, $englishTranslation2->getTranslatable());
        $this->assertSame($translations['en'], $englishTranslation2);

        $this->assertTrue($translations->contains($germanTranslation));
        $this->assertSame($entity, $germanTranslation->getTranslatable());
        $this->assertArrayHasKey('de', $translations);
    }

    public function testCanInitialiseWithArrayCollectionContainingDuplicates(): void
    {
        $englishTranslation1 = new TranslatableEntityTranslation('en');
        $englishTranslation2 = new TranslatableEntityTranslation('en');
        $germanTranslation = new TranslatableEntityTranslation('de');

        $entity = new TranslatableEntity(
            new ArrayCollection(
                [
                    $englishTranslation1,
                    $englishTranslation2,
                    $germanTranslation
                ]
            )
        );

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(2, $translations);

        $this->assertFalse($translations->contains($englishTranslation1));
        $this->assertArrayHasKey('en', $translations);
        $this->assertNotSame($translations['en'], $englishTranslation1);

        $this->assertTrue($translations->contains($englishTranslation2));
        $this->assertSame($entity, $englishTranslation2->getTranslatable());
        $this->assertSame($translations['en'], $englishTranslation2);

        $this->assertTrue($translations->contains($germanTranslation));
        $this->assertSame($entity, $germanTranslation->getTranslatable());
        $this->assertArrayHasKey('de', $translations);
    }

    public function testThrowsExceptionOnInitWithInvalidTranslation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid translation.');

        new TranslatableEntity(
            [
                new TranslatableEntityTranslation('en'),
                new \stdClass()
            ]
        );
    }

    public function testThrowsExceptionOnInitWithTranslationOfOtherEntity(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid translation.');

        new TranslatableEntity(
            [
                new TranslatableEntityTranslation('nl'),
                new OtherEntityTranslation('en')
            ]
        );
    }

    public function testCanClone(): void
    {
        $entity = new TranslatableEntity(
            [
                new TranslatableEntityTranslation('en'),
                new TranslatableEntityTranslation('nl')
            ]
        );

        $translations = $entity->getTranslations();

        $this->assertCount(2, $translations);
        $this->assertArrayHasKey('en', $translations);
        $this->assertArrayHasKey('nl', $translations);

        /** @var TranslationInterface $translation */
        foreach ($translations as $translation) {
            $this->assertSame($entity, $translation->getTranslatable());
        }

        $clone = clone $entity;

        $translations = $clone->getTranslations();

        $this->assertCount(2, $translations);
        $this->assertArrayHasKey('en', $translations);
        $this->assertArrayHasKey('nl', $translations);

        // Make sure that translatable has been set to the cloned entity of each translation.
        /** @var TranslationInterface $translation */
        foreach ($translations as $translation) {
            $translatable = $translation->getTranslatable();
            $this->assertSame($clone, $translatable);
            $this->assertNotSame($entity, $translatable);
        }
    }

    public function testCanAddTranslation(): void
    {
        $entity = new TranslatableEntity();
        $translation = new TranslatableEntityTranslation('en');

        $entity->addTranslation($translation);

        $translations = $entity->getTranslations();

        $this->assertCount(1, $translations);
        $this->assertTrue($translations->contains($translation));
        $this->assertSame($entity, $translation->getTranslatable());
    }

    public function testThrowsExceptionOnAddWithTranslationOfOtherEntity(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid translation.');

        $entity = new TranslatableEntity();

        $entity->addTranslation(new OtherEntityTranslation('en'));
    }

    public function testCanRemoveTranslation(): void
    {
        $translationToRemove = new TranslatableEntityTranslation('en');
        $entity = new TranslatableEntity(
            [
                $translationToRemove,
                new TranslatableEntityTranslation('nl')
            ]
        );

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(2, $translations);
        $this->assertTrue($translations->contains($translationToRemove));
        $this->assertArrayHasKey('en', $translations);
        $this->assertArrayHasKey('nl', $translations);

        // Remove translation
        $entity->removeTranslation($translationToRemove);

        $translations = $entity->getTranslations();

        $this->assertCount(1, $translations);
        $this->assertFalse($translations->contains($translationToRemove));
        $this->assertArrayNotHasKey('en', $translations);
    }

    public function testHasTranslation(): void
    {
        $entity = new TranslatableEntity(
            [
                new TranslatableEntityTranslation('en'),
                new TranslatableEntityTranslation('nl')
            ]
        );

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(2, $translations);

        $this->assertTrue($entity->hasTranslation('en'));
        $this->assertTrue($entity->hasTranslation('nl'));
        $this->assertFalse($entity->hasTranslation(''));
        $this->assertFalse($entity->hasTranslation('de'));
        $this->assertFalse($entity->hasTranslation('en_US'));
    }

    public function testHasTranslationThrowsOnInvalidLocale(): void
    {
        $entity = new TranslatableEntity();

        // Generate random string longer than maximum allowed size.
        $locale = bin2hex(\random_bytes((\INTL_MAX_LOCALE_LEN / 2) + 10));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid locale.');

        $entity->hasTranslation($locale);
    }

    public function testCanSetTranslationsWhenEmpty(): void
    {
        $entity = new TranslatableEntity();

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(0, $translations);

        $entity->setTranslations(
            [
                new TranslatableEntityTranslation('en'),
                new TranslatableEntityTranslation('nl')
            ]
        );

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(2, $translations);

        $this->assertArrayHasKey('en', $translations);
        $this->assertArrayHasKey('nl', $translations);
        $this->assertSame($entity, $translations['en']->getTranslatable());
        $this->assertSame($entity, $translations['nl']->getTranslatable());
    }

    public function testSetTranslationsRemovesOtherTranslations(): void
    {
        $entity = new TranslatableEntity(
            [
                new TranslatableEntityTranslation('de')
            ]
        );

        $translations = $entity->getTranslations();

        // Make sure we have the DE translation.
        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(1, $translations);
        $this->assertArrayHasKey('de', $translations);

        $entity->setTranslations(
            [
                new TranslatableEntityTranslation('en'),
                new TranslatableEntityTranslation('nl')
            ]
        );

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(2, $translations);

        // Make sure we have EN and NL translations and that the DE translation is removed.
        $this->assertArrayHasKey('en', $translations);
        $this->assertArrayHasKey('nl', $translations);
        $this->assertArrayNotHasKey('de', $translations);
    }

    public function testSetTranslationsReplacesExistingTranslationsOfNewInstance(): void
    {
        $existingTranslation = new TranslatableEntityTranslation('en');

        $entity = new TranslatableEntity(
            [
                $existingTranslation
            ]
        );

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(1, $translations);
        $this->assertArrayHasKey('en', $translations);
        $this->assertSame($existingTranslation, $translations['en']);

        $entity->setTranslations(
            [
                new TranslatableEntityTranslation('en'),
                new TranslatableEntityTranslation('nl')
            ]
        );

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(2, $translations);

        $this->assertArrayHasKey('en', $translations);
        $this->assertArrayHasKey('nl', $translations);
        $this->assertNotSame($existingTranslation, $translations['en']);
        $this->assertNull($existingTranslation->getTranslatable());
    }

    public function testSetTranslationsIgnoresExistingTranslationsOfSameInstance(): void
    {
        $existingTranslation = new TranslatableEntityTranslation('en');

        $entity = new TranslatableEntity(
            [
                $existingTranslation
            ]
        );

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(1, $translations);
        $this->assertArrayHasKey('en', $translations);
        $this->assertSame($existingTranslation, $translations['en']);

        $existingTranslation->id = 33;

        $entity->setTranslations(
            [
                $existingTranslation
            ]
        );

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(1, $translations);

        $this->assertArrayHasKey('en', $translations);
        $this->assertSame($existingTranslation, $translations['en']);
        $this->assertSame($entity, $existingTranslation->getTranslatable());
    }

    public function testThrowsExceptionOnSetTranslationsWithTranslationOfOtherEntity(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid translation.');

        $entity = new TranslatableEntity();

        $entity->setTranslations(
            [
                new TranslatableEntityTranslation('nl'),
                new OtherEntityTranslation('en')
            ]
        );
    }

    public function testTranslateEmptyWithNewLocale(): void
    {
        $entity = new TranslatableEntity();

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(0, $translations);

        // Call translate with new locale.
        $newTranslation = $entity->translate('en');

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(1, $translations);
        $this->assertArrayHasKey('en', $translations);
        $this->assertSame($newTranslation, $translations['en']);
    }

    public function testTranslateWithNewLocale(): void
    {
        $entity = new TranslatableEntity(
            [
                new TranslatableEntityTranslation('de')
            ]
        );

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(1, $translations);
        $this->assertArrayHasKey('de', $translations);

        // Call translate with new locale.
        $newTranslation = $entity->translate('en');

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(2, $translations);
        $this->assertArrayHasKey('de', $translations);
        $this->assertArrayHasKey('en', $translations);
        $this->assertSame($newTranslation, $translations['en']);
    }

    public function testTranslateWithExistingLocale(): void
    {
        $entity = new TranslatableEntity();

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(0, $translations);

        // Call translate with new locale.
        $newTranslation = $entity->translate('en');

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(1, $translations);
        $this->assertArrayHasKey('en', $translations);
        $this->assertSame($newTranslation, $translations['en']);

        $this->assertSame($entity->translate('en'), $newTranslation);
    }

    public function testTranslateThrowsOnInvalidLocale(): void
    {
        $entity = new TranslatableEntity();

        // Generate random string longer than maximum allowed size.
        $locale = bin2hex(\random_bytes((\INTL_MAX_LOCALE_LEN / 2) + 10));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid locale.');

        $entity->translate($locale);
    }

    public function testTranslateReturnsFirstTranslationInArray(): void
    {
        $entity = new TranslatableEntity(
            [
                new TranslatableEntityTranslation('en'),
                new TranslatableEntityTranslation('nl'),
                new TranslatableEntityTranslation('de')
            ]
        );

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(3, $translations);
        $this->assertArrayHasKey('en', $translations);
        $this->assertArrayHasKey('nl', $translations);
        $this->assertArrayHasKey('de', $translations);

        $translation = $entity->translate(['nl', 'de']);

        $this->assertSame($translation, $translations['nl']);
        $this->assertSame('nl', $translation->getLocale());
        $this->assertSame($entity, $translation->getTranslatable());
    }

    public function testTranslateReturnsFallbackTranslationInArray(): void
    {
        $entity = new TranslatableEntity(
            [
                new TranslatableEntityTranslation('en'),
                new TranslatableEntityTranslation('nl'),
                new TranslatableEntityTranslation('de')
            ]
        );

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(3, $translations);
        $this->assertArrayHasKey('en', $translations);
        $this->assertArrayHasKey('nl', $translations);
        $this->assertArrayHasKey('de', $translations);

        $translation = $entity->translate(['it', 'fr', 'de', 'nl']);

        $this->assertSame($translation, $translations['de']);
        $this->assertSame('de', $translation->getLocale());
        $this->assertSame($entity, $translation->getTranslatable());
    }

    public function testTranslateThrowsWhenTranslationNotFoundInArray(): void
    {
        $entity = new TranslatableEntity(
            [
                new TranslatableEntityTranslation('en'),
                new TranslatableEntityTranslation('nl'),
                new TranslatableEntityTranslation('de')
            ]
        );

        $translations = $entity->getTranslations();

        $this->assertInstanceOf(Collection::class, $translations);
        $this->assertCount(3, $translations);
        $this->assertArrayHasKey('en', $translations);
        $this->assertArrayHasKey('nl', $translations);
        $this->assertArrayHasKey('de', $translations);

        $this->expectException(TranslationNotFoundException::class);

        $entity->translate(['it', 'fr']);
    }

    public function testThrowsExceptionOnSetInvalidLocaleOnTranslation(): void
    {
        $translation = new TranslatableEntityTranslation();

        // Generate random string longer than maximum allowed size.
        $locale = bin2hex(\random_bytes((\INTL_MAX_LOCALE_LEN / 2) + 10));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid locale.');

        $translation->setLocale($locale);
    }
}
