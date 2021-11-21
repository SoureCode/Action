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

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class JobDefinition
{
    /**
     * @var list<string>
     */
    private array $dependencies = [];

    private bool $continueOnError = false;

    private string $name;

    /**
     * @var array<string, TaskDefinition>
     */
    private array $tasks = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addTask(TaskDefinition $definition): void
    {
        $this->tasks[$definition->getName()] = $definition;
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    public function setDependencies(array $dependencies): void
    {
        $this->dependencies = $dependencies;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array<string, TaskDefinition>
     */
    public function getTasks(): array
    {
        return $this->tasks;
    }

    /**
     * @param array<string, TaskDefinition> $tasks
     */
    public function setTasks(array $tasks): void
    {
        $this->tasks = $tasks;
    }

    public function continueOnError(): bool
    {
        return $this->continueOnError;
    }

    public function setContinueOnError(bool $continueOnError): void
    {
        $this->continueOnError = $continueOnError;
    }
}
