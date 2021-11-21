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
use SoureCode\Component\Action\Task;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class TaskTest extends TestCase
{
    public function testExecute(): void
    {
        $task = new Task('ls', __DIR__);

        $output = $task->execute();

        $this->assertStringContainsString(basename(__FILE__), $output);
    }

    public function testGetterAndConstructor(): void
    {
        $task = new Task('ls -lar', '/tmp');

        $this->assertEquals('ls -lar', $task->getCommand());
        $this->assertEquals('/tmp', $task->getDirectory());
    }

    public function testPipe(): void
    {
        $task = new Task('ls | grep Task', __DIR__);

        $output = $task->execute();

        $this->assertStringContainsString(basename(__FILE__), $output);
        $this->assertStringContainsString('TaskDefinitionTest.php', $output);
        $this->assertStringNotContainsString('DependencyResolverTest.php', $output);
    }

    public function testFailedTest(): void
    {
        $this->expectException(ProcessFailedException::class);

        $task = new Task('ls -ö', __DIR__);

        $task->execute();
    }
}
