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

    public function execute(?callable $callback = null, ?string $input = null): void
    {
        $process = $this->getProcess();
        $process->setInput($input);
        $process->mustRun(
            null !== $callback ? static function (string $type, string $data) use ($callback) {
                $callback($data, $type);
            } : null
        );
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
