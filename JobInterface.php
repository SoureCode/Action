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

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
interface JobInterface
{
    public function execute(OutputInterface $output): void;
}
