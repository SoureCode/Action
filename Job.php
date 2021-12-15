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

use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

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
            $section = $output instanceof ConsoleOutput ? $output->section() : $output;
            $task = $this->taskFactory->fromDefinition($taskDefinition);
            $inputKey = $taskDefinition->getInputKey();
            $outputKey = $taskDefinition->getOutputKey();
            $input = $inputKey ? $this->storage->get($inputKey) : null;
            $taskName = $taskDefinition->getName();

            $section->writeln(sprintf(' ➤ Executing task <info>%s</info>', $taskName));

            if ($section->isVeryVerbose()) {
                $section->writeln(sprintf(' - InputKey: <info>%s</info>', $inputKey ?? '-'));
                $section->writeln(sprintf(' - OutputKey: <info>%s</info>', $outputKey ?? '-'));
                $section->writeln(sprintf(' - Input: <info>%s</info>', json_encode($input, \JSON_THROW_ON_ERROR)));
            }

            try {
                $taskOutput = new BufferedOutput();

                $task->execute(function (string $data) use ($taskOutput, $section) {
                    $taskOutput->write($data);

                    if ($section instanceof ConsoleSectionOutput) {
                        /** @var ConsoleSectionOutput $section */
                        $bufferedData = $taskOutput->fetch();

                        $section->overwrite($bufferedData);
                        $taskOutput->write($bufferedData);
                    } else {
                        $section->write($data);
                    }
                }, $input);

                if (null !== $outputKey) {
                    $this->storage->set($outputKey, $taskOutput->fetch());
                }

                $message = sprintf(' <fg=green>✔</> Task <info>%s</info> executed', $taskName);

                if ($section instanceof ConsoleSectionOutput) {
                    $section->overwrite($message);
                } else {
                    $section->writeln($message);
                }
            } catch (Throwable $exception) {
                if (!$taskDefinition->continueOnError()) {
                    $message = sprintf('<error> ✘ Task <fg=green;bg=red>%s</> failed</error>', $taskName);
                } else {
                    $message = sprintf('<gf=yellow> ● Task <info>%s</info> failed</>', $taskName);
                }

                if ($section instanceof ConsoleSectionOutput) {
                    $section->overwrite($message);
                } else {
                    $section->writeln($message);
                }

                if (!$taskDefinition->continueOnError()) {
                    throw $exception;
                }
            }
        }
    }
}
