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
class TaskFactory
{
    public function fromDefinition(TaskDefinition $definition): TaskInterface
    {
        return new Task(
            $definition->getCommand(),
            $definition->getDirectory(),
        );
    }
}
