<?php

namespace Cloudstek\DoctrineBehaviour\Tests\Subscriber;

use Cloudstek\DoctrineBehaviour\Subscriber\TranslatableSubscriber;
use Cloudstek\DoctrineBehaviour\Tests\Fixtures\Translatable\OtherEntityTranslation;
use Cloudstek\DoctrineBehaviour\Tests\Fixtures\Translatable\TranslatableEntity;
use Cloudstek\DoctrineBehaviour\Tests\Fixtures\Translatable\TranslatableEntityTranslation;
use Cloudstek\DoctrineBehaviour\Tests\Fixtures\Translatable\TranslatableEntityWithoutTranslation;
use Cloudstek\DoctrineBehaviour\Tests\Fixtures\Translatable\TranslationEntity;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\MappingException;

/**
 * @covers \Cloudstek\DoctrineBehaviour\Subscriber\TranslatableSubscriber
 */
class TranslatableSubscriberTest extends AbstractSubscriberTestCase
{
    public function testIsEventSubscriber(): void
    {
        $this->assertInstanceOf(EventSubscriber::class, new TranslatableSubscriber());
    }

    /**
     * @covers \Cloudstek\DoctrineBehaviour\Subscriber\TranslatableSubscriber::getSubscribedEvents()
     */
    public function testSubscribedEvents(): void
    {
        $subscriber = new TranslatableSubscriber();

        $this->assertEquals(
            [Events::loadClassMetadata],
            $subscriber->getSubscribedEvents()
        );
    }

    /**
     * @covers \Cloudstek\DoctrineBehaviour\Subscriber\TranslatableSubscriber::loadClassMetadata()
     * @covers \Cloudstek\DoctrineBehaviour\Subscriber\TranslatableSubscriber::mapTranslatable()
     */
    public function testLoadClassMetadataForTranslatable(): void
    {
        $entityClass = TranslatableEntity::class;
        $em = $this->createEntityManager();
        $metadata = $em->getClassMetadata($entityClass);

        // Make sure we have no translations association.
        $this->assertNotNull($metadata);
        $this->assertFalse($metadata->hasAssociation('translations'));

        // Trigger loadClassMetadata event.
        $translatableSubscriber = new TranslatableSubscriber();
        $translatableSubscriber->loadClassMetadata(new LoadClassMetadataEventArgs($metadata, $em));

        // Check if the association has been mapped.
        $metadata = $em->getClassMetadata($entityClass);

        $this->assertNotNull($metadata);

        $this->assertTrue($metadata->hasAssociation('translations'));
        $this->assertEquals(
            [
                'fieldName' => 'translations',
                'targetEntity' => TranslatableEntityTranslation::class,
                'mappedBy' => 'translatable',
                'fetch' => ClassMetadataInfo::FETCH_EXTRA_LAZY,
                'indexBy' => 'locale',
                'cascade' => ['persist'],
                'orphanRemoval' => true,
                'type' => ClassMetadataInfo::ONE_TO_MANY,
                'inversedBy' => null,
                'isOwningSide' => false,
                'sourceEntity' => $entityClass,
                'isCascadeRemove' => true,
                'isCascadePersist' => true,
                'isCascadeRefresh' => false,
                'isCascadeMerge' => false,
                'isCascadeDetach' => false
            ],
            $metadata->getAssociationMapping('translations')
        );
    }

    /**
     * @covers \Cloudstek\DoctrineBehaviour\Subscriber\TranslatableSubscriber::loadClassMetadata()
     * @covers \Cloudstek\DoctrineBehaviour\Subscriber\TranslatableSubscriber::mapTranslatable()
     */
    public function testLoadClassMetadataForTranslatableWithoutTranslationClass(): void
    {
        $entityClass = TranslatableEntityWithoutTranslation::class;
        $em = $this->createEntityManager();
        $metadata = $em->getClassMetadata($entityClass);

        // Make sure we have no mapping yet.
        $this->assertNotNull($metadata);
        $this->assertFalse($metadata->hasAssociation('translations'));

        // Expect exception
        $this->expectException(MappingException::class);
        $this->expectExceptionMessage("Translation class {$entityClass}Translation not found.");

        // Trigger loadClassMetadata event.
        $translatableSubscriber = new TranslatableSubscriber();
        $translatableSubscriber->loadClassMetadata(new LoadClassMetadataEventArgs($metadata, $em));
    }

    /**
     * @covers \Cloudstek\DoctrineBehaviour\Subscriber\TranslatableSubscriber::loadClassMetadata()
     * @covers \Cloudstek\DoctrineBehaviour\Subscriber\TranslatableSubscriber::mapTranslation()
     */
    public function testLoadClassMetadataForTranslation(): void
    {
        $entityClass = TranslatableEntityTranslation::class;
        $em = $this->createEntityManager();
        $metadata = $em->getClassMetadata($entityClass);

        // Make sure we have no mapping yet.
        $this->assertNotNull($metadata);
        $this->assertFalse($metadata->hasAssociation('translatable'));
        $this->assertFalse($metadata->hasField('locale'));
        $this->assertEmpty($metadata->table['uniqueConstraints'] ?? []);

        // Trigger loadClassMetadata event.
        $translatableSubscriber = new TranslatableSubscriber();
        $translatableSubscriber->loadClassMetadata(new LoadClassMetadataEventArgs($metadata, $em));

        // Check if the association has been mapped.
        $metadata = $em->getClassMetadata($entityClass);

        $this->assertNotNull($metadata);
        $this->assertTrue($metadata->hasAssociation('translatable'));

        $this->assertEquals(
            [
                'fieldName' => 'translatable',
                'targetEntity' => TranslatableEntity::class,
                'mappedBy' => null,
                'fetch' => ClassMetadataInfo::FETCH_LAZY,
                'cascade' => [],
                'orphanRemoval' => false,
                'type' => ClassMetadataInfo::MANY_TO_ONE,
                'inversedBy' => 'translations',
                'isOwningSide' => true,
                'sourceEntity' => $entityClass,
                'isCascadeRemove' => false,
                'isCascadePersist' => false,
                'isCascadeRefresh' => false,
                'isCascadeMerge' => false,
                'isCascadeDetach' => false,
                'joinColumns' => [
                    [
                        'name' => 'translatable_id',
                        'referencedColumnName' => 'id',
                        'onDelete' => 'CASCADE',
                        'nullable' => false
                    ]
                ],
                'joinColumnFieldNames' => [
                    'translatable_id' => 'translatable_id'
                ],
                'targetToSourceKeyColumns' => [
                    'id' => 'translatable_id'
                ],
                'sourceToTargetKeyColumns' => [
                    'translatable_id' => 'id'
                ]
            ],
            $metadata->getAssociationMapping('translatable')
        );

        // Check locale field
        $this->assertTrue($metadata->hasField('locale'));
        $this->assertEquals(
            [
                'fieldName' => 'locale',
                'type' => 'string',
                'length' => 12,
                'columnName' => 'locale'
            ],
            $metadata->getFieldMapping('locale')
        );

        // Check unique constraints
        $this->assertNotEmpty($metadata->table['uniqueConstraints'] ?? []);

        $this->assertEquals(
            [
                "{$metadata->getTableName()}_uniq_trans" => [
                    'columns' => [
                        'translatable_id',
                        'locale'
                    ]
                ]
            ],
            $metadata->table['uniqueConstraints']
        );
    }

    /**
     * @covers \Cloudstek\DoctrineBehaviour\Subscriber\TranslatableSubscriber::loadClassMetadata()
     * @covers \Cloudstek\DoctrineBehaviour\Subscriber\TranslatableSubscriber::mapTranslation()
     */
    public function testLoadClassMetadataForTranslationWithoutTranslatableClass(): void
    {
        $entityClass = OtherEntityTranslation::class;
        $entityTranslatableClass = \preg_replace('/Translation$/', '', $entityClass);

        $em = $this->createEntityManager();
        $metadata = $em->getClassMetadata($entityClass);

        // Make sure we have no mapping yet.
        $this->assertNotNull($metadata);
        $this->assertFalse($metadata->hasAssociation('translations'));

        // Expect exception
        $this->expectException(MappingException::class);
        $this->expectExceptionMessage("Translatable class {$entityTranslatableClass} not found.");

        // Trigger loadClassMetadata event.
        $translatableSubscriber = new TranslatableSubscriber();
        $translatableSubscriber->loadClassMetadata(new LoadClassMetadataEventArgs($metadata, $em));
    }

    /**
     * @covers \Cloudstek\DoctrineBehaviour\Subscriber\TranslatableSubscriber::loadClassMetadata()
     * @covers \Cloudstek\DoctrineBehaviour\Subscriber\TranslatableSubscriber::mapTranslation()
     */
    public function testLoadClassMetadataForTranslationWithWrongName(): void
    {
        $entityClass = TranslationEntity::class;

        $em = $this->createEntityManager();
        $metadata = $em->getClassMetadata($entityClass);

        // Make sure we have no mapping yet.
        $this->assertNotNull($metadata);
        $this->assertFalse($metadata->hasAssociation('translations'));

        // Expect exception
        $this->expectException(MappingException::class);
        $this->expectExceptionMessage("Translation class name should be {$entityClass}Translation.");

        // Trigger loadClassMetadata event.
        $translatableSubscriber = new TranslatableSubscriber();
        $translatableSubscriber->loadClassMetadata(new LoadClassMetadataEventArgs($metadata, $em));
    }
}
