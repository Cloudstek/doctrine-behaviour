<?php

namespace Cloudstek\DoctrineBehaviour\Filter;

use Carbon\CarbonImmutable;
use Cloudstek\DoctrineBehaviour\ExpirableInterface;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * Expirable SQL filter.
 *
 * Filters all expired entities from query results.
 */
class ExpirableFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if (!$targetEntity->getReflectionClass()->implementsInterface(ExpirableInterface::class)) {
            return '';
        }

        // Get connection and database platform
        $connection = $this->getConnection();
        $platform = $connection->getDatabasePlatform();

        // Get quoted column name
        $column = $targetTableAlias . '.expires_at';

        // Return constraint where deletedAt is NULL or in the future
        return sprintf(
            '%1$s IS NULL OR %1$s > %2$s',
            $platform->quoteIdentifier($column),
            $platform->quoteStringLiteral(
                Type::getType('datetime_immutable')
                    ->convertToDatabaseValue(CarbonImmutable::now('UTC'), $platform)
            )
        );
    }
}
