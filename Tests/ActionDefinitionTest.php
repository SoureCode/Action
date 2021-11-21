<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Component\Action\Tests;

use PHPUnit\Framework\TestCase;
use SoureCode\Component\Action\ActionDefinition;
use SoureCode\Component\Action\JobDefinition;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class ActionDefinitionTest extends TestCase
{
    public function testGetterAndSetter(): void
    {
        $jobDefinition = new JobDefinition('foo');
        $jobDefinition1 = new JobDefinition('bar');

        $actionDefinition = new ActionDefinition('foo');
        $actionDefinition->setDependencies(['bar']);
        $actionDefinition->setJobs([$jobDefinition]);
        $actionDefinition->addJob($jobDefinition1);

        $this->assertEquals('foo', $actionDefinition->getName());
        $this->assertEquals(['bar'], $actionDefinition->getDependencies());
        $this->assertEquals([$jobDefinition, $jobDefinition1], $actionDefinition->getJobs());
    }
}
