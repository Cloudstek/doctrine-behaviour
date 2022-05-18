<?php

declare(strict_types=1);

namespace Cloudstek\DoctrineBehaviour\Tests\Fixtures;

use Cloudstek\DoctrineBehaviour\ExpirableTrait;
use Cloudstek\DoctrineBehaviour\SoftDeletableTrait;
use Cloudstek\DoctrineBehaviour\TimestampableTrait;

class TestEntityWithoutInterfaces
{
    use ExpirableTrait;
    use SoftDeletableTrait;
    use TimestampableTrait;
}
