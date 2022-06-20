<?php

namespace Cloudstek\DoctrineBehaviour\Tests\Subscriber;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMSetup;
use PHPUnit\Framework\TestCase;

abstract class AbstractSubscriberTestCase extends TestCase
{
    /**
     * @throws ORMException
     */
    protected function createEntityManager(?array $paths = null): EntityManagerInterface
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            $paths ?? [dirname(__DIR__) . '/Fixtures'],
            true,
        );

        $dbParams = [
            'url' => 'sqlite:///:memory:'
        ];

        return EntityManager::create($dbParams, $config);
    }
}
