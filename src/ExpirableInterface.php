<?php

declare(strict_types=1);

namespace Cloudstek\DoctrineBehaviour;

/**
 * Expirable interface.
 */
interface ExpirableInterface
{
    /**
     * Get expiration date.
     *
     * @return \DateTimeImmutable|null
     */
    public function getExpiresAt(): ?\DateTimeImmutable;

    /**
     * Set expiration date.
     *
     * @param \DateTime|\DateTimeImmutable|null $date
     *
     * @return $this
     */
    public function setExpiresAt(\DateTimeImmutable|\DateTime|null $date): self;

    /**
     * Expire in x years/months/days/hours ...
     *
     * @param \DateInterval|string|null $time
     *
     * @return $this
     */
    public function setExpiresIn(\DateInterval|string|null $time): self;

    /**
     * Get if expired.
     *
     * @return bool
     */
    public function isExpired(): bool;

    /**
     * Expire immediately.
     *
     * @return $this
     */
    public function expire(): self;

    /**
     * Unexpire.
     *
     * @return $this
     */
    public function unexpire(): self;
}
