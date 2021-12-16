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

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class Task implements TaskInterface
{
    private string $command;

    private string $directory;

    private Filesystem $filesystem;

    private ?Process $process = null;

    public function __construct(
        Filesystem $filesystem,
        string $command,
        string $directory,
    ) {
        $this->filesystem = $filesystem;
        $this->command = $command;
        $this->directory = $directory;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(?callable $callback = null, array $inputs = []): void
    {
        $file = $this->createTemporaryFile($inputs);

        try {
            $environment = ['APP_ENV' => false, 'SYMFONY_DOTENV_VARS' => false];
            $command = 'bash --noprofile --norc -e -o pipefail '.$file;

            $process = Process::fromShellCommandline($command, $this->directory, $environment);

            $process->mustRun(
                null !== $callback ? static function (string $type, string $data) use ($callback) {
                    $callback($data, $type);
                } : null
            );
        } finally {
            $this->filesystem->remove($file);
        }
    }

    /**
     * @param array<string, string> $inputs
     */
    private function createTemporaryFile(array $inputs = []): string
    {
        $temporaryDirectory = realpath(sys_get_temp_dir());
        $temporaryFile = tempnam($temporaryDirectory, 'sourecode-action').'.sh';

        if (!$temporaryFile) {
            throw new \RuntimeException(sprintf('Could not create temporary file in "%s"', $temporaryDirectory));
        }

        $stub = "#!/usr/bin/env bash\n\n";
        $stub .= "set -e\n\n";

        if (!empty($inputs)) {
            foreach ($inputs as $key => $value) {
                $stub .= sprintf('export INPUT_%s="%s"', strtoupper($key), $value);
            }

            $stub .= "\n\n";
        }

        $stub .= $this->command;
        $stub .= "\n"; // EOF

        $this->filesystem->dumpFile($temporaryFile, $stub);
        $this->filesystem->chmod($temporaryFile, 0755);

        return $temporaryFile;
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
