<?php

namespace Cloudstek\DoctrineBehaviour\Tests;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Cloudstek\DoctrineBehaviour\SoftDeletableTrait;
use Cloudstek\DoctrineBehaviour\Tests\Assertions\DateAssertions;
use Cloudstek\DoctrineBehaviour\Tests\Fixtures\SoftDeletableEntity;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SoftDeletableTrait::class)]
class SoftDeletableTest extends TestCase
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

    public function testCanSetDeletedAtWithMutable()
    {
        $entity = new SoftDeletableEntity();
        $date = Carbon::now();
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $date);

        $this->assertNull($entity->getDeletedAt());

        $entity->setDeletedAt($date);

        $this->assertDateEquals($date, $entity->getDeletedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getDeletedAt());
    }

    public function testCanSetDeletedAtWithImmutable()
    {
        $entity = new SoftDeletableEntity();
        $date = CarbonImmutable::now();
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $date);

        $this->assertNull($entity->getDeletedAt());

        $entity->setDeletedAt($date);

        $this->assertDateEquals($date, $entity->getDeletedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getDeletedAt());
    }

    public function testCanUnsetDeletedAt()
    {
        $entity = new SoftDeletableEntity();
        $date = Carbon::now();
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $date);

        $this->assertNull($entity->getDeletedAt());

        $entity->setDeletedAt($date);

        $this->assertDateEquals($date, $entity->getDeletedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getDeletedAt());

        $entity->setDeletedAt(null);

        $this->assertNull($entity->getDeletedAt());
    }

    public function testIsDeletedWithPastDate()
    {
        $entity = new SoftDeletableEntity();
        $date = CarbonImmutable::now()->subHours(2);
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $date);

        $this->assertNull($entity->getDeletedAt());

        $entity->setDeletedAt($date);

        $this->assertTrue($entity->isDeleted());
    }

    public function testIsDeletedWithCurrentDate()
    {
        $entity = new SoftDeletableEntity();
        $date = CarbonImmutable::now();
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $date);

        $this->assertNull($entity->getDeletedAt());

        $entity->setDeletedAt($date);

        $this->assertTrue($entity->isDeleted());
    }

    public function testIsDeletedWithFutureDate()
    {
        $entity = new SoftDeletableEntity();
        $date = CarbonImmutable::now()->addHours(3);

        $this->assertNull($entity->getDeletedAt());

        $entity->setDeletedAt($date);

        $this->assertTrue($entity->isDeleted());
    }

    public function testCanDeleteImmediately()
    {
        $entity = new SoftDeletableEntity();
        $date = Carbon::now();
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $date);

        $this->assertNull($entity->getDeletedAt());

        $entity->delete();

        $this->assertTrue($entity->isDeleted());
        $this->assertDateEquals($date, $entity->getDeletedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getDeletedAt());
    }

    public function testCanRecoverImmediately()
    {
        $entity = new SoftDeletableEntity();
        $date = Carbon::now();
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $date);

        $this->assertNull($entity->getDeletedAt());

        $entity->setDeletedAt($date);

        $this->assertDateEquals($date, $entity->getDeletedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getDeletedAt());

        $entity->recover();

        $this->assertNull($entity->getDeletedAt());
        $this->assertFalse($entity->isDeleted());
    }
}
