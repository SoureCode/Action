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
use SoureCode\Component\Action\DependencyResolver;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class DependencyResolverTest extends TestCase
{
    public function testResolve()
    {
        $resolver = new DependencyResolver();

        $resolver->addDependency('lorem', []);
        $resolver->addDependency('dolor', ['ipsum']);
        $resolver->addDependency('foo', ['bar']);
        $resolver->addDependency('bar', ['batz']);
        $resolver->addDependency('batz', []);
        $resolver->addDependency('ipsum', ['foo']);

        $result = $resolver->resolve();

        self::assertSame(['lorem', 'batz', 'bar', 'foo', 'ipsum', 'dolor'], $result);
    }

    public function testResolveSingle()
    {
        $resolver = new DependencyResolver();

        $resolver->addDependency('lorem', []);
        $resolver->addDependency('dolor', ['ipsum']);
        $resolver->addDependency('foo', ['bar']);
        $resolver->addDependency('bar', ['batz']);
        $resolver->addDependency('batz', []);
        $resolver->addDependency('ipsum', ['foo']);

        $result = $resolver->resolveSingle('foo');

        self::assertSame(['batz', 'bar', 'foo'], $result);
    }
}
