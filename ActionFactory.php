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
class ActionFactory
{
    private JobFactory $jobFactory;

    public function __construct(JobFactory $jobFactory)
    {
        $this->jobFactory = $jobFactory;
    }

    #[Pure]
    public function fromDefinition(ActionDefinition $definition): Action
    {
        return new Action($this->jobFactory, $definition->getJobs());
    }
}
