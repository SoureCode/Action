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
use SoureCode\Component\Action\TaskDefinition;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class TaskDefinitionTest extends TestCase
{
    public function testGetterAndSetter(): void
    {
        $taskDefinition = new TaskDefinition('test', 'ls');

        $taskDefinition->setContinueOnError(true);
        $taskDefinition->setInputKeys(['foo']);
        $taskDefinition->setOutputKey('bar');
        $taskDefinition->setDirectory('/tmp');

        $this->assertEquals('test', $taskDefinition->getName());
        $this->assertEquals('ls', $taskDefinition->getCommand());
        $this->assertTrue($taskDefinition->continueOnError());
        $this->assertEquals(['foo'], $taskDefinition->getInputKeys());
        $this->assertEquals('bar', $taskDefinition->getOutputKey());
        $this->assertEquals('/tmp', $taskDefinition->getDirectory());
    }
}
