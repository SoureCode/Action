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
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class Task implements TaskInterface
{
    private string $command;

    private string $directory;

    private ?Process $process = null;

    public function __construct(
        string $command,
        string $directory,
    ) {
        $this->command = $command;
        $this->directory = $directory;
    }

    public function execute(?string $input = null): string
    {
        $process = $this->getProcess();
        $process->setInput($input);
        $process->start();

        $output = new BufferedOutput();

        foreach ($process as $data) {
            $output->write($data);
        }

        $exitCode = $process->wait();

        if (0 !== $exitCode) {
            throw new ProcessFailedException($process);
        }

        return $output->fetch();
    }

    public function getProcess(): Process
    {
        if (!$this->process) {
            $this->process = Process::fromShellCommandline(
                $this->command,
                $this->directory,
                ['APP_ENV' => false, 'SYMFONY_DOTENV_VARS' => false],
            );
        }

        return $this->process;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }
}
