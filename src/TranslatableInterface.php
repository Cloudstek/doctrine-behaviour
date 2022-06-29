<?php

declare(strict_types=1);

namespace Cloudstek\DoctrineBehaviour;

use Cloudstek\DoctrineBehaviour\Exception\TranslationNotFoundException;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Selectable;

/**
 * Translatable interface.
 *
 * @template T of TranslationInterface
 */
interface TranslatableInterface
{
    /**
     * Get translations.
     *
     * @return Collection<string, T>&Selectable
     */
    public function getTranslations(): Collection;

    /**
     * Set translations.
     *
     * @param iterable<array-key, T> $translations
     *
     * @return $this
     */
    public function setTranslations(iterable $translations): self;

    /**
     * Add translation.
     *
     * @param T $translation
     *
     * @return $this
     */
    public function addTranslation(TranslationInterface $translation): self;

    /**
     * Remove translation
     *
     * @param T $translation
     *
     * @return $this
     */
    public function removeTranslation(TranslationInterface $translation): self;

    /**
     * Check if translation exists.
     *
     * @param string $locale
     *
     * @return bool
     */
    public function hasTranslation(string $locale): bool;

    /**
     * Get or create translation.
     *
     * @param string|string[] $locale      Translation locale, if a string and the translation is missing it will be
     *                                     created. When an array, it will return the preferred translation based on
     *                                     the order of the array or throw an exception; no translation will be created
     *                                     in this case.
     *
     * @throws TranslationNotFoundException When locale parameter is an array and none of the translations for these
     *                                      locales could be found.
     *
     * @return T
     */
    public function translate(string|array $locale): TranslationInterface;
}
