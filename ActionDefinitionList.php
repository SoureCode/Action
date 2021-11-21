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
class ActionDefinitionList
{
    /**
     * @var array<string, ActionDefinition>
     */
    private array $actionDefinitions;

    /**
     * @param array<string, ActionDefinition> $actionDefinitions
     */
    public function __construct(array $actionDefinitions = [])
    {
        $this->actionDefinitions = $actionDefinitions;
    }

    public function get(string $name): ActionDefinition
    {
        if (!isset($this->actionDefinitions[$name])) {
            throw new \InvalidArgumentException(sprintf('Action "%s" does not exist.', $name));
        }

        return $this->actionDefinitions[$name];
    }

    public function getActionDefinitions(): array
    {
        return $this->actionDefinitions;
    }

    public function has(string $name): bool
    {
        return isset($this->actionDefinitions[$name]);
    }

    public function set(string $name, ActionDefinition $actionDefinition): void
    {
        $this->actionDefinitions[$name] = $actionDefinition;
    }
}
