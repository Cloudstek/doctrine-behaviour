<?php

declare(strict_types=1);

namespace Cloudstek\DoctrineBehaviour;

use Carbon\CarbonImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Expirable trait.
 *
 * Adds expiresAt field for entities that can expire.
 */
trait ExpirableTrait
{
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $expiresAt = null;

    /**
     * Get expiration date.
     *
     * @return \DateTimeImmutable|null
     */
    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    /**
     * Set expiration date.
     *
     * @param \DateTimeInterface|null $date
     *
     * @return $this
     */
    public function setExpiresAt(?\DateTimeInterface $date): self
    {
        if ($date !== null && $date instanceof \DateTimeImmutable === false) {
            $date = \DateTimeImmutable::createFromInterface($date);
        }

        $this->expiresAt = $date?->setTimezone(new \DateTimeZone('UTC'));

        return $this;
    }

    /**
     * Expire in x years/months/days/hours ...
     *
     * @param \DateInterval|string|null $time
     *
     * @return $this
     */
    public function setExpiresIn(\DateInterval|string|null $time): self
    {
        $date = null;

        if (empty($time) === false) {
            if (is_string($time)) {
                $time = \DateInterval::createFromDateString($time);
            }

            $date = CarbonImmutable::now('UTC')->add($time)->toDateTimeImmutable();
        }

        $this->expiresAt = $date;

        return $this;
    }

    /**
     * Check if expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        if (isset($this->expiresAt) && $this->getExpiresAt() <= CarbonImmutable::now('UTC')) {
            return true;
        }

        return false;
    }

    /**
     * Expire immediately.
     *
     * @return $this
     */
    public function expire(): self
    {
        $this->setExpiresAt(CarbonImmutable::now('UTC'));

        return $this;
    }

    /**
     * Unexpire.
     *
     * @return $this
     */
    public function unexpire(): self
    {
        $this->expiresAt = null;

        return $this;
    }
}
