<?php

namespace Cloudstek\DoctrineBehaviour\Tests;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Cloudstek\DoctrineBehaviour\Tests\Assertions\DateAssertions;
use Cloudstek\DoctrineBehaviour\Tests\Fixtures\Timestampable\TimestampableEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Cloudstek\DoctrineBehaviour\TimestampableTrait
 */
class TimestampableTest extends TestCase
{
    use DateAssertions;

    protected function setUp(): void
    {
        parent::setUp();

        // Freeze time
        $now = Carbon::now('Europe/Amsterdam');

        Carbon::setTestNowAndTimezone($now);
        CarbonImmutable::setTestNowAndTimezone($now);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Unfreeze time
        Carbon::setTestNowAndTimezone();
        CarbonImmutable::setTestNowAndTimezone();
    }

    public function testCanSetCreatedAtWithMutable()
    {
        $entity = new TimestampableEntity();
        $date = Carbon::now()->addHours(3);
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $date);

        $this->assertNull($entity->getCreatedAt());
        $this->assertNull($entity->getUpdatedAt());

        $entity->setCreatedAt($date);

        $this->assertDateEquals($date, $entity->getCreatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getCreatedAt());
        $this->assertNull($entity->getUpdatedAt());
    }

    public function testCanSetCreatedAtWithImmutable()
    {
        $entity = new TimestampableEntity();
        $date = CarbonImmutable::now()->addHours(3);
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $date);

        $this->assertNull($entity->getCreatedAt());
        $this->assertNull($entity->getUpdatedAt());

        $entity->setCreatedAt($date);

        $this->assertDateEquals($date, $entity->getCreatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getCreatedAt());
        $this->assertNull($entity->getUpdatedAt());
    }

    public function testCanSetUpdatedAtWithMutable()
    {
        $entity = new TimestampableEntity();
        $date = Carbon::now()->addHours(3);
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $date);

        $this->assertNull($entity->getCreatedAt());
        $this->assertNull($entity->getUpdatedAt());

        $entity->setUpdatedAt($date);

        $this->assertNull($entity->getCreatedAt());
        $this->assertDateEquals($date, $entity->getUpdatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getUpdatedAt());
    }

    public function testCanSetUpdatedAtWithImmutable()
    {
        $entity = new TimestampableEntity();
        $date = CarbonImmutable::now()->addHours(3);
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $date);

        $this->assertNull($entity->getCreatedAt());
        $this->assertNull($entity->getUpdatedAt());

        $entity->setUpdatedAt($date);

        $this->assertNull($entity->getCreatedAt());
        $this->assertDateEquals($date, $entity->getUpdatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getUpdatedAt());
    }
}
