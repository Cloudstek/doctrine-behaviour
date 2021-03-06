<?php

declare(strict_types=1);

namespace Cloudstek\DoctrineBehaviour;

/**
 * Timestampable interface.
 */
interface TimestampableInterface
{
    /**
     * Get creation date.
     *
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable;

    /**
     * Set creation date.
     *
     * @param \DateTime|\DateTimeImmutable $date
     *
     * @return $this
     */
    public function setCreatedAt(\DateTime|\DateTimeImmutable $date): self;

    /**
     * Get last updated date.
     *
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?\DateTimeImmutable;

    /**
     * Set last updated date.
     *
     * @param \DateTime|\DateTimeImmutable $date
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTime|\DateTimeImmutable $date): self;
}
