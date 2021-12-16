<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Component\Action\Tests;

use PHPUnit\Framework\TestCase;
use SoureCode\Component\Action\ActionDefinition;
use SoureCode\Component\Action\ActionFactory;
use SoureCode\Component\Action\JobDefinition;
use SoureCode\Component\Action\JobFactory;
use SoureCode\Component\Action\MemoryStorage;
use SoureCode\Component\Action\TaskFactory;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class ActionFactoryTest extends TestCase
{
    public function testFromDefinition(): void
    {
        $filesystem = new Filesystem();
        $storage = new MemoryStorage();
        $taskFactory = new TaskFactory($filesystem);
        $jobFactory = new JobFactory($storage, $taskFactory);
        $actionFactory = new ActionFactory($jobFactory);

        $actionDefinition = new ActionDefinition('test');
        $actionDefinition->setDependencies(['bar']);
        $actionDefinition->addJob(new JobDefinition('bar'));

        $action = $actionFactory->fromDefinition($actionDefinition);

        self::assertNotNull($action);
    }
}
