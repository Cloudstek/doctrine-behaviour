<?php

namespace Cloudstek\DoctrineBehaviour;

/**
 * Translation trait.
 *
 * @template T of TranslatableInterface
 * @implements TranslationInterface<T>
 */
trait TranslationTrait
{
    protected string $locale;

    /** @var T|null */
    protected ?TranslatableInterface $translatable = null;

    /**
     * Get locale.
     *
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Set locale.
     *
     * @param string $locale
     *
     * @return $this
     */
    public function setLocale(string $locale): self
    {
        $locale = \Locale::canonicalize($locale);

        if ($locale === null) {
            throw new \InvalidArgumentException('Invalid locale.');
        }

        $this->locale = $locale;

        return $this;
    }

    /**
     * Get translatable.
     *
     * @return T|null
     */
    public function getTranslatable(): ?TranslatableInterface
    {
        return $this->translatable;
    }

    /**
     * Set translatable.
     *
     * @param T|null $translatable
     *
     * @return $this
     */
    public function setTranslatable(?TranslatableInterface $translatable = null): self
    {
        $this->translatable = $translatable;

        return $this;
    }
}
