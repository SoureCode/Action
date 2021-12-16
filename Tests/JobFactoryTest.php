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
use SoureCode\Component\Action\JobDefinition;
use SoureCode\Component\Action\JobFactory;
use SoureCode\Component\Action\MemoryStorage;
use SoureCode\Component\Action\TaskDefinition;
use SoureCode\Component\Action\TaskFactory;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class JobFactoryTest extends TestCase
{
    public function testFromDefinition(): void
    {
        $filesystem = new Filesystem();
        $taskFactory = new TaskFactory($filesystem);
        $storage = new MemoryStorage();
        $jobFactory = new JobFactory($storage, $taskFactory);

        $taskDefinition = new TaskDefinition('bar', 'ls');

        $taskDefinition->setContinueOnError(true);
        $taskDefinition->setInputKeys(['foo']);
        $taskDefinition->setOutputKey('bar');
        $taskDefinition->setDirectory('/tmp');

        $taskDefinitionFoo = new TaskDefinition('foo', 'ls');

        $taskDefinitionFoo->setContinueOnError(true);
        $taskDefinitionFoo->setInputKeys(['foo']);
        $taskDefinitionFoo->setOutputKey('bar');
        $taskDefinitionFoo->setDirectory('/tmp');

        $jobDefinition = new JobDefinition('foo');
        $jobDefinition->setDependencies(['bar']);
        $jobDefinition->setContinueOnError(false);
        $jobDefinition->setTasks([
            $taskDefinition,
        ]);
        $jobDefinition->addTask($taskDefinitionFoo);

        $job = $jobFactory->fromDefinition($jobDefinition);

        self::assertNotNull($job);
    }
}
