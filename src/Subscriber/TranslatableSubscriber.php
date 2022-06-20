<?php

declare(strict_types=1);

namespace Cloudstek\DoctrineBehaviour\Subscriber;

use Cloudstek\DoctrineBehaviour\TranslatableInterface;
use Cloudstek\DoctrineBehaviour\TranslationInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Util\ClassUtils as DoctrineClassUtils;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\Persistence\Mapping\ClassMetadata;

/**
 * Translatable event subscriber.
 *
 * This event subscriber adds the required mapping for translatable entities and their translations.
 */
class TranslatableSubscriber implements EventSubscriber
{
    /**
     * @inheritDoc
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::loadClassMetadata
        ];
    }

    /**
     * Load class metadata.
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     *
     * @throws MappingException
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();

        if ($classMetadata->reflClass->isAbstract()) {
            return;
        }

        if ($classMetadata->reflClass->implementsInterface(TranslatableInterface::class)) {
            $this->mapTranslatable($classMetadata);
        } elseif ($classMetadata->reflClass->implementsInterface(TranslationInterface::class)) {
            $this->mapTranslation($classMetadata);
        }
    }

    /**
     * Map translatable entity.
     *
     * @param ClassMetadata $classMetadata
     *
     * @throws MappingException
     */
    private function mapTranslatable(ClassMetadata $classMetadata): void
    {
        // Get real class name (in case of proxy).
        $className = DoctrineClassUtils::getRealClass($classMetadata->getName());

        // Check if translation class exists.
        if (class_exists($className . 'Translation') === false) {
            throw new MappingException("Translation class {$className}Translation not found.");
        }

        // Map translations.
        $classMetadata->mapOneToMany(
            [
                'fieldName' => 'translations',
                'targetEntity' => "{$className}Translation",
                'mappedBy' => 'translatable',
                'fetch' => ClassMetadataInfo::FETCH_EXTRA_LAZY,
                'indexBy' => 'locale',
                'cascade' => ['persist'],
                'orphanRemoval' => true,
            ]
        );
    }

    /**
     * Map translation of translatable entity.
     *
     * @param ClassMetadata $classMetadata
     *
     * @throws MappingException
     */
    private function mapTranslation(ClassMetadata $classMetadata): void
    {
        // Get real class name (in case of proxy).
        $className = DoctrineClassUtils::getRealClass($classMetadata->getName());

        // Check class name.
        if (str_ends_with($className, 'Translation') === false) {
            throw new MappingException("Translation class name should be {$className}Translation.");
        }

        // Check if translatable class exists.
        $translatableClassName = \preg_replace('/Translation$/', '', $className);

        if (class_exists($translatableClassName) === false) {
            throw new MappingException("Translatable class {$translatableClassName} not found.");
        }

        // Map translatable.
        $classMetadata->mapManyToOne(
            [
                'fieldName' => 'translatable',
                'targetEntity' => $translatableClassName,
                'inversedBy' => 'translations',
                'joinColumns' => [
                    [
                        'name' => 'translatable_id',
                        'referencedColumnName' => 'id',
                        'onDelete' => 'CASCADE',
                        'nullable' => false
                    ]
                ]
            ]
        );

        // Map locale field.
        $classMetadata->mapField(
            [
                'fieldName' => 'locale',
                'type' => 'string',
                'length' => 12
            ]
        );

        // Get existing unique constraints (if any).
        $uniqueConstraints = $classMetadata->table['uniqueConstraints'] ?? [];

        // Add unique constraint.
        $uniqueConstraints["{$classMetadata->getTableName()}_uniq_trans"] = [
            'columns' => [$classMetadata->getSingleAssociationJoinColumnName('translatable'), 'locale']
        ];

        // Add it to the class metadata.
        $classMetadata->setPrimaryTable(
            [
                'uniqueConstraints' => $uniqueConstraints
            ]
        );
    }
}
