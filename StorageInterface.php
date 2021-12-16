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
interface StorageInterface
{
    public function clear(): void;

    public function get(string $key): ?string;

    public function set(string $key, string $value): void;

    public function has(string $key): bool;
}
