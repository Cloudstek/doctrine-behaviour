<?php

declare(strict_types=1);

namespace Cloudstek\DoctrineBehaviour;

use Carbon\CarbonImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Soft-deletable trait.
 *
 * Adds deletedAt field for soft-deletion of an entity. When deletedAt is set to a date in the future, it behaves the
 * same way like the ExpirableTrait.
 */
trait SoftDeletableTrait
{
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    protected ?\DateTimeImmutable $deletedAt = null;

    /**
     * Get deletion date.
     *
     * @return \DateTimeImmutable|null
     */
    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    /**
     * Set deletion date.
     *
     * @param \DateTimeInterface|null $date
     *
     * @return $this
     */
    public function setDeletedAt(?\DateTimeInterface $date): self
    {
        if ($date !== null && $date instanceof \DateTimeImmutable === false) {
            $date = \DateTimeImmutable::createFromInterface($date);
        }

        $this->deletedAt = $date?->setTimezone(new \DateTimeZone('UTC'));

        return $this;
    }

    /**
     * Check if deleted.
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    /**
     * Delete.
     *
     * @return $this
     */
    public function delete(): self
    {
        $this->setDeletedAt(CarbonImmutable::now('UTC'));

        return $this;
    }

    /**
     * Recover (undelete).
     *
     * @return $this
     */
    public function recover(): self
    {
        $this->setDeletedAt(null);

        return $this;
    }
}
