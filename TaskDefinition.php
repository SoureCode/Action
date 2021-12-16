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
class TaskDefinition
{
    private string $command;

    private bool $continueOnError = false;

    private ?string $directory = null;

    private array $inputKeys = [];

    private string $name;

    private ?string $outputKey = null;

    public function __construct(string $name, string $command)
    {
        $this->command = $command;
        $this->name = $name;
    }

    public function continueOnError(): bool
    {
        return $this->continueOnError;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function setCommand(string $command): void
    {
        $this->command = $command;
    }

    public function getDirectory(): string
    {
        if (!$this->directory) {
            return getcwd();
        }

        return $this->directory;
    }

    public function setDirectory(string $directory): void
    {
        $this->directory = $directory;
    }

    public function getInputKeys(): array
    {
        return $this->inputKeys;
    }

    public function setInputKeys(array $inputKeys): void
    {
        $this->inputKeys = $inputKeys;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getOutputKey(): ?string
    {
        return $this->outputKey;
    }

    public function setOutputKey(?string $outputKey): void
    {
        $this->outputKey = $outputKey;
    }

    public function setContinueOnError(bool $continueOnError): void
    {
        $this->continueOnError = $continueOnError;
    }
}
