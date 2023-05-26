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
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    protected ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
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
     * @param \DateTimeInterface $date
     *
     * @return $this
     */
    public function setCreatedAt(\DateTimeInterface $date): self
    {
        if ($date instanceof \DateTimeImmutable === false) {
            $date = \DateTimeImmutable::createFromInterface($date);
        }

        $this->createdAt = $date->setTimezone(new \DateTimeZone('UTC'));;

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
     * @param \DateTimeInterface $date
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTimeInterface $date): self
    {
        if ($date instanceof \DateTimeImmutable === false) {
            $date = \DateTimeImmutable::createFromInterface($date);
        }

        $this->updatedAt = $date->setTimezone(new \DateTimeZone('UTC'));;

        return $this;
    }
}
