<?php

declare(strict_types=1);

namespace Cloudstek\DoctrineBehaviour;

/**
 * Translation interface.
 *
 * @template T of TranslatableInterface
 */
interface TranslationInterface
{
    /**
     * Get locale.
     *
     * @return string
     */
    public function getLocale(): string;

    /**
     * Set locale.
     *
     * @param string $locale
     *
     * @return $this
     */
    public function setLocale(string $locale): self;

    /**
     * Get translatable.
     *
     * @return T|null
     */
    public function getTranslatable(): ?TranslatableInterface;

    /**
     * Set translatable.
     *
     * @param T|null $translatable
     *
     * @return $this
     */
    public function setTranslatable(?TranslatableInterface $translatable): self;
}
