# Documentation

## Timestampable

Adds createdAt and updatedAt properties to an entity. A listener automatically makes sure these properties are populated
on creation and updating.

[Documentation](./Timestampable.md)

## Expirable

Adds expiredAt property to an entity with methods to for example easily expire and unexpire the entity. Use
the [expirable filter](../src/Filter/ExpirableFilter.php) to filter expired entities from query results.

[Documentation](./Expirable.md)

## Soft-deletable

Adds deletedAt property to an entity with methods to for example easily soft-delete and recover the entity. Use
the [soft-delete filter](../src/Filter/SoftDeleteFilter.php) to filter soft-deleted entities from query results.

[Documentation](./Expirable.md)

## Translatable

[Documentation](./Translatable.md)
