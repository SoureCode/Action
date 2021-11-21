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
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class Action implements ActionInterface
{
    /**
     * @var array<string, JobDefinition>
     */
    private array $jobDefinitions;

    private JobFactory $jobFactory;

    /**
     * @param array<string, JobDefinition> $jobDefinitions
     */
    public function __construct(JobFactory $jobFactory, array $jobDefinitions)
    {
        $this->jobFactory = $jobFactory;
        $this->jobDefinitions = $jobDefinitions;
    }

    public function execute(OutputInterface $output): void
    {
        $jobNames = $this->getDependencyResolver()->resolve();

        $this->executeJobs($output, $jobNames);
    }

    private function getDependencyResolver(): DependencyResolver
    {
        $dependencyResolver = new DependencyResolver();

        foreach ($this->jobDefinitions as $name => $definition) {
            $dependencyResolver->addDependency($name, $definition->getDependencies());
        }

        return $dependencyResolver;
    }

    private function executeJobs(OutputInterface $output, array $jobNames): void
    {
        foreach ($jobNames as $name) {
            $jobDefinition = $this->jobDefinitions[$name];
            $job = $this->jobFactory->fromDefinition($jobDefinition);

            try {
                $job->execute($output);
            } catch (ProcessFailedException $exception) {
                if (!$jobDefinition->continueOnError()) {
                    throw $exception;
                }
            }
        }
    }

    public function executeJob(OutputInterface $output, string $name): void
    {
        $jobNames = $this->getDependencyResolver()->resolveSingle($name);

        $this->executeJobs($output, $jobNames);
    }
}
