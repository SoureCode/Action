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
use SoureCode\Component\Action\Job;
use SoureCode\Component\Action\MemoryStorage;
use SoureCode\Component\Action\Task;
use SoureCode\Component\Action\TaskDefinition;
use SoureCode\Component\Action\TaskFactory;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class JobTest extends TestCase
{
    public function testExecute(): void
    {
        $storage = new MemoryStorage();
        $taskDefinition = $this->createMock(TaskDefinition::class);
        $taskDefinition1 = $this->createMock(TaskDefinition::class);
        $taskMock = $this->createMock(Task::class);
        $taskMock1 = $this->createMock(Task::class);
        $taskFactory = $this->createMock(TaskFactory::class);
        $taskFactory
            ->method('fromDefinition')
            ->willReturn($taskMock, $taskMock1);

        $taskMock->expects($this->once())
            ->method('execute');

        $taskMock1->expects($this->once())
            ->method('execute');

        $job = new Job($storage, $taskFactory, [$taskDefinition, $taskDefinition1]);

        $output = new BufferedOutput();

        $job->execute($output);
    }

    public function testOutput()
    {
        $storage = new MemoryStorage();
        $taskDefinition = $this->createMock(TaskDefinition::class);
        $taskMock = $this->createMock(Task::class);
        $taskFactory = $this->createMock(TaskFactory::class);
        $taskFactory
            ->method('fromDefinition')
            ->willReturn($taskMock);

        $taskDefinition
            ->method('getOutputKey')
            ->willReturn('foo');

        $taskMock
            ->method('execute')
            ->willReturn('test bar foo');

        $job = new Job($storage, $taskFactory, [$taskDefinition]);

        $output = new BufferedOutput();

        $job->execute($output);

        self::assertSame('test bar foo', $storage->get('foo'));
    }
}
