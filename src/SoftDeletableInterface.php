<?php

declare(strict_types=1);

namespace Cloudstek\DoctrineBehaviour;

/**
 * Soft-deletable interface.
 */
interface SoftDeletableInterface
{
    /**
     * Get deletion date.
     *
     * @return \DateTimeImmutable|null
     */
    public function getDeletedAt(): ?\DateTimeImmutable;

    /**
     * Set deletion date.
     *
     * @param \DateTime|\DateTimeImmutable|null $date
     *
     * @return $this
     */
    public function setDeletedAt(\DateTime|\DateTimeImmutable|null $date): self;

    /**
     * Get if entity has been soft-deleted.
     *
     * @return bool
     */
    public function isDeleted(): bool;

    /**
     * Soft delete entity.
     *
     * @return $this
     */
    public function delete(): self;

    /**
     * Reciver deleted entity.
     *
     * @return $this
     */
    public function recover(): self;
}
