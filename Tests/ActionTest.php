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
use SoureCode\Component\Action\Action;
use SoureCode\Component\Action\Job;
use SoureCode\Component\Action\JobDefinition;
use SoureCode\Component\Action\JobFactory;
use Symfony\Component\Console\Output\BufferedOutput;

class ActionTest extends TestCase
{
    public function testExecute(): void
    {
        $job = $this->createMock(Job::class);
        $job
            ->expects($this->once())
            ->method('execute');

        $job1 = $this->createMock(Job::class);
        $job1
            ->expects($this->once())
            ->method('execute');

        $jobFactory = $this->createMock(JobFactory::class);
        $jobFactory
            ->expects($this->exactly(2))
            ->method('fromDefinition')
            ->willReturn($job, $job1);

        $jobDefinition = new JobDefinition('foo');
        $jobDefinition->setDependencies(['bar']);

        $jobDefinition1 = new JobDefinition('bar');
        $jobDefinition1->setDependencies([]);

        $action = new Action($jobFactory, [
            'foo' => $jobDefinition,
            'bar' => $jobDefinition1,
        ]);

        $output = new BufferedOutput();
        $action->execute($output);
    }

    public function testExecuteJob(): void
    {
        $job = $this->createMock(Job::class);
        $job
            ->expects($this->once())
            ->method('execute');

        $job1 = $this->createMock(Job::class);
        $job1
            ->expects($this->once())
            ->method('execute');

        $jobFactory = $this->createMock(JobFactory::class);
        $jobFactory
            ->expects($this->exactly(2))
            ->method('fromDefinition')
            ->willReturn($job, $job1);

        $jobDefinition = new JobDefinition('foo');
        $jobDefinition->setDependencies(['bar']);

        $jobDefinition1 = new JobDefinition('bar');
        $jobDefinition1->setDependencies([]);

        $action = new Action($jobFactory, [
            'foo' => $jobDefinition,
            'bar' => $jobDefinition1,
        ]);

        $output = new BufferedOutput();
        $action->executeJob($output, 'foo');
    }

    public function testExecuteJobWithoutDependency(): void
    {
        $job1 = $this->createMock(Job::class);
        $job1->expects($this->once())
            ->method('execute');

        $jobFactory = $this->createMock(JobFactory::class);
        $jobFactory
            ->expects($this->once())
            ->method('fromDefinition')
            ->willReturn($job1);

        $jobDefinition = new JobDefinition('foo');
        $jobDefinition->setDependencies(['bar']);

        $jobDefinition1 = new JobDefinition('bar');
        $jobDefinition1->setDependencies([]);

        $action = new Action($jobFactory, [
            'foo' => $jobDefinition,
            'bar' => $jobDefinition1,
        ]);

        $output = new BufferedOutput();
        $action->executeJob($output, 'bar');
    }
}
