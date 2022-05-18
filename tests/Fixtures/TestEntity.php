<?php

declare(strict_types=1);

namespace Cloudstek\DoctrineBehaviour\Tests\Fixtures;

use Cloudstek\DoctrineBehaviour\ExpirableInterface;
use Cloudstek\DoctrineBehaviour\ExpirableTrait;
use Cloudstek\DoctrineBehaviour\SoftDeletableInterface;
use Cloudstek\DoctrineBehaviour\SoftDeletableTrait;
use Cloudstek\DoctrineBehaviour\TimestampableInterface;
use Cloudstek\DoctrineBehaviour\TimestampableTrait;

class TestEntity implements ExpirableInterface, SoftDeletableInterface, TimestampableInterface
{
    use ExpirableTrait;
    use SoftDeletableTrait;
    use TimestampableTrait;
}
