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

use JetBrains\PhpStorm\Pure;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class JobFactory
{
    private StorageInterface $storage;

    private TaskFactory $taskFactory;

    public function __construct(StorageInterface $storage, TaskFactory $taskFactory)
    {
        $this->storage = $storage;
        $this->taskFactory = $taskFactory;
    }

    #[Pure]
    public function fromDefinition(JobDefinition $definition): JobInterface
    {
        return new Job(
            $this->storage,
            $this->taskFactory,
            $definition->getTasks(),
        );
    }
}
