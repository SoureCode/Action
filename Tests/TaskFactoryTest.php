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
use SoureCode\Component\Action\TaskFactory;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class TaskFactoryTest extends TestCase
{
    public function testFromDefinition(): void
    {
        $taskFactory = new TaskFactory();

        $taskDefinition = new TaskDefinition('bar', 'ls');

        $taskDefinition->setContinueOnError(true);
        $taskDefinition->setInputKey('foo');
        $taskDefinition->setOutputKey('bar');
        $taskDefinition->setDirectory('/tmp');

        $task = $taskFactory->fromDefinition($taskDefinition);

        self::assertNotNull($task);
    }
}
