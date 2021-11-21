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

use Exception;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class DependencyResolver
{
    /**
     * @var array<string, list<string>>
     */
    private array $dependencies = [];

    private array $temporary = [];

    private array $visited = [];

    /**
     * @param list<string> $value
     */
    public function addDependency(string $name, array $value): void
    {
        $this->dependencies[$name] = $value;
    }

    public function resolveSingle(string $node): array
    {
        $this->temporary = [];
        $this->visited = [];
        $items = [];

        $this->visit($node, $items);

        return $items;
    }

    public function resolve(): array
    {
        $this->temporary = [];
        $this->visited = [];
        $items = [];

        while ($name = $this->getNextUnvisited()) {
            $this->visit($name, $items);
        }

        return $items;
    }

    private function getNextUnvisited(): string|null
    {
        foreach ($this->dependencies as $name => $dependencies) {
            if (!isset($this->visited[$name])) {
                return $name;
            }
        }

        return null;
    }

    private function visit(string $node, array &$items): void
    {
        if (isset($this->visited[$node])) {
            return;
        }

        if (isset($this->temporary[$node])) {
            throw new Exception('not a DAG');
        }

        $this->temporary[$node] = true;

        foreach ($this->dependencies[$node] as $dependency) {
            $this->visit($dependency, $items);
        }

        unset($this->temporary[$node]);
        $this->visited[$node] = true;
        $items[] = $node;
    }
}
