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

class MemoryStorage implements StorageInterface
{
    /**
     * @var array<string, string>
     */
    private array $storage = [];

    public function clear(): void
    {
        $this->storage = [];
    }

    public function get(string $key): ?string
    {
        return $this->storage[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return \array_key_exists($key, $this->storage);
    }

    public function set(string $key, string $value): void
    {
        $this->storage[$key] = $value;
    }
}
