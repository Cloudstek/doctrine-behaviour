<?php

namespace Cloudstek\DoctrineBehaviour\Tests\Assertions;

use Carbon\CarbonImmutable;

trait DateAssertions
{
    /**
     * Assert date equals.
     *
     * Before comparing both dates are converted to UTC and then compared.
     *
     * @param \DateTimeInterface      $expected
     * @param \DateTimeInterface|null $actual
     *
     * @return void
     */
    public function assertDateEquals(
        \DateTimeInterface $expected,
        ?\DateTimeInterface $actual
    ): void {
        $expected = CarbonImmutable::parse($expected)->setTimezone('UTC');

        if ($actual !== null) {
            $actual = CarbonImmutable::parse($actual)->setTimezone('UTC');
        }

        static::assertEquals(
            $expected->format(\DateTimeInterface::ATOM),
            $actual?->format(\DateTimeInterface::ATOM)
        );
    }

    /**
     * Assert date timezone equals.
     *
     * @param string|\DateTimeZone                  $expected
     * @param \DateTimeZone|\DateTimeInterface|null $actual
     *
     * @return void
     */
    public function assertDateTimezoneEquals(
        string|\DateTimeZone $expected,
        \DateTimeZone|\DateTimeInterface|null $actual
    ): void {
        if (is_string($expected)) {
            $expected = new \DateTimeZone($expected);
        }

        if ($actual instanceof \DateTimeInterface) {
            $actual = $actual->getTimezone();
        }

        static::assertEquals($expected->getName(), $actual->getName());
    }
}
