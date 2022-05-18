# Cloudstek Doctrine Behaviour

> Library of different entity behaviours (timestampable etc.)

## Requirements

- PHP 8.1+
- Doctrine ORM 2

## Installation

```shell
$ composer require cloudstek/doctrine-behaviour
```

## Behaviours

### Timestampable

Adds createdAt and updatedAt properties to an entity. A listener automatically makes sure these properties are populated
on creation and updating.

### Expirable

Adds expiredAt property to an entity with methods to for example easily expire and unexpire the entity. Use
the [expirable filter](./src/Filter/ExpirableFilter.php) to filter expired entities from query results.

### Soft-deletable

Adds deletedAt property to an entity with methods to for example easily soft-delete and recover the entity. Use
the [soft-delete filter](./src/Filter/SoftDeleteFilter.php) to filter soft-deleted entities from query results.

## Usage

Extensive documentation has not been written but have a look at the [tests](./tests).

1. Create your entity class
2. Make it implement the behaviour interface (e.g. [TimestampableInterface](./src/TimestampableInterface.php))
3. Use the matching trait to implement the required methods for the chosen behaviour interface (
   e.g. [TimestampableTrait](./src/TimestampableTrait.php)) or write your own version of it.
4. In case of timestampable entities, register the [entity listener](./src/Listener/TimestampableListener.php) for your
   entity, other behaviours don't require a listener. If you want this done automatically, use
   the [timestampable subscriber](./src/Subscriber/TimestampableSubscriber.php) to have this done automatically for each
   timestampable entity.
5. Set up [filters](https://www.doctrine-project.org/projects/doctrine-orm/en/2.11/reference/filters.html) in case of
   expirable or soft-deletable entities. See [ExpirableFilter](./src/Filter/ExpirableFilter.php)
   and [SoftDeleteFilter](./src/Filter/SoftDeleteFilter.php).
