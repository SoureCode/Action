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

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class TaskFactory
{
    private Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function fromDefinition(TaskDefinition $definition): TaskInterface
    {
        return new Task(
            $this->filesystem,
            $definition->getCommand(),
            $definition->getDirectory(),
        );
    }
}
