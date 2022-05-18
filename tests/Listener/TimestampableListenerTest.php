<?php

namespace Cloudstek\DoctrineBehaviour\Tests\Listener;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Cloudstek\DoctrineBehaviour\Listener\TimestampableListener;
use Cloudstek\DoctrineBehaviour\Tests\Assertions\DateAssertions;
use Cloudstek\DoctrineBehaviour\Tests\Fixtures\TestEntity;
use Cloudstek\DoctrineBehaviour\Tests\Fixtures\TestEntityWithoutInterfaces;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Cloudstek\DoctrineBehaviour\TimestampableTrait
 * @covers \Cloudstek\DoctrineBehaviour\Listener\TimestampableListener
 */
class TimestampableListenerTest extends TestCase
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

    public function testPrePersistWithoutDatesSet(): void
    {
        $entity = new TestEntity();
        $listener = new TimestampableListener();
        $objectManager = $this->createStub(ObjectManager::class);

        $date = Carbon::now();
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $date);

        $this->assertNull($entity->getCreatedAt());
        $this->assertNull($entity->getUpdatedAt());

        $listener->prePersist($entity, new LifecycleEventArgs($entity, $objectManager));

        // Make sure createdAt and updatedAt are current time
        $this->assertDateEquals($date, $entity->getCreatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getCreatedAt());

        $this->assertDateEquals($date, $entity->getUpdatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getUpdatedAt());
    }

    public function testPrePersistWithCreationDateSet(): void
    {
        $entity = new TestEntity();
        $listener = new TimestampableListener();
        $objectManager = $this->createStub(ObjectManager::class);

        $now = CarbonImmutable::now();
        $created = $now->subHours(3);
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $now);
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $created);

        $entity->setCreatedAt($created);

        $this->assertDateEquals($created, $entity->getCreatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getCreatedAt());
        $this->assertNull($entity->getUpdatedAt());

        $listener->prePersist($entity, new LifecycleEventArgs($entity, $objectManager));

        // Make sure createdAt is not modified
        $this->assertDateEquals($created, $entity->getCreatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getCreatedAt());

        // Make sure updatedAt is current time
        $this->assertDateEquals($now, $entity->getUpdatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getUpdatedAt());
    }

    public function testPrePersistWithBothDatesSet(): void
    {
        $entity = new TestEntity();
        $listener = new TimestampableListener();
        $objectManager = $this->createStub(ObjectManager::class);

        $now = CarbonImmutable::now();
        $created = $now->subHours(3);
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $now);
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $created);

        $entity->setCreatedAt($created);
        $entity->setUpdatedAt($created);

        $this->assertDateEquals($created, $entity->getCreatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getCreatedAt());
        $this->assertDateEquals($created, $entity->getUpdatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getUpdatedAt());

        $listener->prePersist($entity, new LifecycleEventArgs($entity, $objectManager));

        // Make sure createdAt and updatedAt are not modified
        $this->assertDateEquals($created, $entity->getCreatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getCreatedAt());

        $this->assertDateEquals($created, $entity->getUpdatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getUpdatedAt());
    }

    public function testPrePersistWithNonTimestampableEntity(): void
    {
        $entity = new TestEntityWithoutInterfaces();
        $listener = new TimestampableListener();
        $objectManager = $this->createStub(ObjectManager::class);

        $date = Carbon::now();
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $date);

        $this->assertNull($entity->getCreatedAt());
        $this->assertNull($entity->getUpdatedAt());

        $listener->prePersist($entity, new LifecycleEventArgs($entity, $objectManager));

        $this->assertNull($entity->getCreatedAt());
        $this->assertNull($entity->getUpdatedAt());
    }

    public function testPreUpdateWithNonTimestampableEntity(): void
    {
        $entity = new TestEntityWithoutInterfaces();
        $listener = new TimestampableListener();
        $em = $this->createStub(EntityManagerInterface::class);

        $date = Carbon::now();
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $date);

        $this->assertNull($entity->getCreatedAt());
        $this->assertNull($entity->getUpdatedAt());

        $changeSet = [];
        $listener->preUpdate($entity, new PreUpdateEventArgs($entity, $em, $changeSet));

        $this->assertNull($entity->getCreatedAt());
        $this->assertNull($entity->getUpdatedAt());
    }

    public function testPreUpdateWithoutDatesSet(): void
    {
        $entity = new TestEntity();
        $listener = new TimestampableListener();
        $em = $this->createStub(EntityManagerInterface::class);

        $now = CarbonImmutable::now();

        $this->assertNull($entity->getCreatedAt());
        $this->assertNull($entity->getUpdatedAt());

        $changeSet = [];
        $listener->preUpdate($entity, new PreUpdateEventArgs($entity, $em, $changeSet));

        // Make sure createdAt is not modified
        $this->assertNull($entity->getCreatedAt());

        // Make sure updatedAt is current time
        $this->assertDateEquals($now, $entity->getUpdatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getUpdatedAt());
    }

    public function testPreUpdateWithCreationDateSet(): void
    {
        $entity = new TestEntity();
        $listener = new TimestampableListener();
        $em = $this->createStub(EntityManagerInterface::class);

        $now = CarbonImmutable::now();
        $created = $now->subHours(3);
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $now);
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $created);

        $entity->setCreatedAt($created);

        $this->assertDateEquals($created, $entity->getCreatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getCreatedAt());
        $this->assertNull($entity->getUpdatedAt());

        $changeSet = [];
        $listener->preUpdate($entity, new PreUpdateEventArgs($entity, $em, $changeSet));

        // Make sure createdAt is not modified
        $this->assertDateEquals($created, $entity->getCreatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getCreatedAt());

        // Make sure updatedAt is current time
        $this->assertDateEquals($now, $entity->getUpdatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getUpdatedAt());
    }

    public function testPreUpdateWithBothDatesSet(): void
    {
        $entity = new TestEntity();
        $listener = new TimestampableListener();
        $em = $this->createStub(EntityManagerInterface::class);

        $now = CarbonImmutable::now();
        $created = $now->subHours(3);
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $now);
        $this->assertDateTimezoneEquals('Europe/Amsterdam', $created);

        $entity->setCreatedAt($created);
        $entity->setUpdatedAt($created);

        $this->assertDateEquals($created, $entity->getCreatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getCreatedAt());
        $this->assertDateEquals($created, $entity->getUpdatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getUpdatedAt());

        $changeSet = [];
        $listener->preUpdate($entity, new PreUpdateEventArgs($entity, $em, $changeSet));

        // Make sure createdAt is not modified
        $this->assertDateEquals($created, $entity->getCreatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getCreatedAt());

        // Make sure updatedAt is current time
        $this->assertDateEquals($now, $entity->getUpdatedAt());
        $this->assertDateTimezoneEquals('UTC', $entity->getUpdatedAt());
    }
}
