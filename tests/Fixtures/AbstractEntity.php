<?php

namespace Cloudstek\DoctrineBehaviour\Tests\Fixtures;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\MappedSuperclass;

#[MappedSuperclass]
abstract class AbstractEntity
{
    #[Id]
    #[Column]
    #[GeneratedValue]
    public ?int $id = null;
}
