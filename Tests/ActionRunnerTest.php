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
use SoureCode\Component\Action\ActionDefinitionList;
use SoureCode\Component\Action\ActionFactory;
use SoureCode\Component\Action\ActionRunner;
use SoureCode\Component\Action\JobDefinition;
use SoureCode\Component\Action\JobFactory;
use SoureCode\Component\Action\MemoryStorage;
use SoureCode\Component\Action\TaskDefinition;
use SoureCode\Component\Action\TaskFactory;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Filesystem\Filesystem;

class ActionRunnerTest extends TestCase
{
    private ?ActionRunner $actionRunner = null;

    private ?MemoryStorage $storage = null;

    public function testExecuteAction(): void
    {
        $output = new BufferedOutput();
        $this->actionRunner->executeAction($output, 'foo');

        $files = scandir(__DIR__);
        $files = array_diff($files, ['.', '..']);

        $this->assertEquals(implode("\n", $files)."\n", $this->storage->get('list'));
        $this->assertEquals(
            implode("\n", [
                'TaskDefinitionTest.php',
                'TaskFactoryTest.php',
                'TaskTest.php',
            ])."\n",
            $this->storage->get('task_test_files')
        );
        $this->assertFalse($this->storage->has('task_test_files_prefixed'));
    }

    public function testExecuteActionWithDependency(): void
    {
        $output = new BufferedOutput();
        $this->actionRunner->executeAction($output, 'bar');

        $files = scandir(__DIR__);
        $files = array_diff($files, ['.', '..']);

        $this->assertEquals(implode("\n", $files)."\n", $this->storage->get('list'));
        $this->assertEquals(
            implode("\n", [
                'TaskDefinitionTest.php',
                'TaskFactoryTest.php',
                'TaskTest.php',
            ])."\n",
            $this->storage->get('task_test_files')
        );
        $this->assertTrue($this->storage->has('task_test_files_prefixed'));
        $this->assertEquals(
            implode("\n", [
                'foo_TaskDefinitionTest.php',
                'foo_TaskFactoryTest.php',
                'foo_TaskTest.php',
            ])."\n",
            $this->storage->get('task_test_files_prefixed')
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $taskDefinition = new TaskDefinition('foofoofoo', 'ls');
        $taskDefinition->setDirectory(__DIR__);
        $taskDefinition->setOutputKey('list');

        $taskDefinition1 = new TaskDefinition('foofoobar', 'echo "$INPUT_LIST" | grep Task');
        $taskDefinition1->setDirectory(__DIR__);
        $taskDefinition1->setInputKeys(['list']);
        $taskDefinition1->setOutputKey('task_test_files');

        $jobDefinition = new JobDefinition('foofoo');
        $jobDefinition->setTasks(['foofoofoo' => $taskDefinition, 'foofoobar' => $taskDefinition1]);

        $taskDefinition2 = new TaskDefinition('foobarfoo', "echo \"\$INPUT_TASK_TEST_FILES\" | xargs -I {} -n 1 sh -c 'echo foo_{}'");
        $taskDefinition2->setDirectory(__DIR__);
        $taskDefinition2->setInputKeys(['task_test_files']);
        $taskDefinition2->setOutputKey('task_test_files_prefixed');

        $jobDefinition1 = new JobDefinition('foobar');
        $jobDefinition1->setTasks(['foobarfoo' => $taskDefinition2]);

        $actionDefinition = new ActionDefinition('foo');
        $actionDefinition->setJobs(['foofoo' => $jobDefinition]);

        $actionDefinition1 = new ActionDefinition('bar');
        $actionDefinition1->setDependencies(['foo']);
        $actionDefinition1->setJobs(['foobar' => $jobDefinition1]);

        $actions = new ActionDefinitionList([
                'foo' => $actionDefinition,
                'bar' => $actionDefinition1,
            ]);

        $filesystem = new Filesystem();
        $this->storage = new MemoryStorage();
        $taskFactory = new TaskFactory($filesystem);
        $jobFactory = new JobFactory($this->storage, $taskFactory);
        $actionFactory = new ActionFactory($jobFactory);

        $this->actionRunner = new ActionRunner($actionFactory, $actions);
    }
}
