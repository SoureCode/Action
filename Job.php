<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Component\Action;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class Job implements JobInterface
{
    private StorageInterface $storage;

    /**
     * @var array<string, TaskDefinition>
     */
    private array $taskDefinitions;

    private TaskFactory $taskFactory;

    /**
     * @param array<string, TaskDefinition> $taskDefinitions
     */
    public function __construct(StorageInterface $storage, TaskFactory $taskFactory, array $taskDefinitions)
    {
        $this->storage = $storage;
        $this->taskFactory = $taskFactory;
        $this->taskDefinitions = $taskDefinitions;
    }

    public function execute(OutputInterface $output): void
    {
        foreach ($this->taskDefinitions as $taskDefinition) {
            $task = $this->taskFactory->fromDefinition($taskDefinition);
            $inputKey = $taskDefinition->getInputKey();
            $outputKey = $taskDefinition->getOutputKey();
            $input = $inputKey ? $this->storage->get($inputKey) : null;

            $taskOutput = $task->execute($input);

            if (null !== $outputKey) {
                if ('console' === $outputKey) {
                    $output->write($taskOutput);
                } else {
                    $this->storage->set($outputKey, $taskOutput);
                }
            }
        }
    }
}
