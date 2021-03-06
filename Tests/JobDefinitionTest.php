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
use SoureCode\Component\Action\TaskDefinition;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class JobDefinitionTest extends TestCase
{
    public function testGetterAndSetter(): void
    {
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
            'bar' => $taskDefinition,
        ]);
        $jobDefinition->addTask($taskDefinitionFoo);

        $this->assertEquals('foo', $jobDefinition->getName());
        $this->assertEquals(['bar'], $jobDefinition->getDependencies());
        $this->assertFalse($jobDefinition->continueOnError());
        $this->assertEquals([
            'bar' => $taskDefinition,
            'foo' => $taskDefinitionFoo,
        ], $jobDefinition->getTasks());
    }
}
