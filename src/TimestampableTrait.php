<?php

declare(strict_types=1);

namespace Cloudstek\DoctrineBehaviour;

use Doctrine\ORM\Mapping as ORM;

/**
 * Timestampable trait.
 *
 * Adds the createdAt field and allows to set creation date/time.
 */
trait TimestampableTrait
{
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    protected ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    protected ?\DateTimeImmutable $updatedAt = null;

    /**
     * Get creation date.
     *
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Set creation date.
     *
     * @param \DateTime|\DateTimeImmutable|null $date
     *
     * @return $this
     */
    public function setCreatedAt(\DateTime|\DateTimeImmutable|null $date): self
    {
        if ($date instanceof \DateTime) {
            $date = \DateTimeImmutable::createFromMutable($date);
        }

        $this->createdAt = $date?->setTimezone(new \DateTimeZone('UTC'));;

        return $this;
    }

    /**
     * Get last updated date.
     *
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Set last updated date.
     *
     * @param \DateTime|\DateTimeImmutable|null $date
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTime|\DateTimeImmutable|null $date): self
    {
        if ($date instanceof \DateTime) {
            $date = \DateTimeImmutable::createFromMutable($date);
        }

        $this->updatedAt = $date?->setTimezone(new \DateTimeZone('UTC'));;

        return $this;
    }
}
