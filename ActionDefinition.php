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
class ActionDefinition
{
    /**
     * @var list<string>
     */
    private array $dependencies = [];

    /**
     * @var array<string, JobDefinition>
     */
    private array $jobs = [];

    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addJob(JobDefinition $job): void
    {
        $this->jobs[$job->getName()] = $job;
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    public function setDependencies(array $dependencies): void
    {
        $this->dependencies = $dependencies;
    }

    /**
     * @return array<string, JobDefinition>
     */
    public function getJobs(): array
    {
        return $this->jobs;
    }

    /**
     * @param array<string, JobDefinition> $jobs
     */
    public function setJobs(array $jobs): void
    {
        $this->jobs = $jobs;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
