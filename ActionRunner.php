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
class ActionRunner
{
    private ActionFactory $actionFactory;

    private ActionDefinitionList $actions;

    public function __construct(ActionFactory $actionFactory, ActionDefinitionList $actions)
    {
        $this->actionFactory = $actionFactory;
        $this->actions = $actions;
    }

    public function executeAction(OutputInterface $output, string $name, ?string $job = null): void
    {
        if (!$this->actions->has($name)) {
            throw new \InvalidArgumentException(sprintf('Action "%s" does not exist', $name));
        }

        if (null !== $job) {
            $action = $this->actions->get($name);
            $jobs = $action->getJobs();

            if (!isset($jobs[$job])) {
                throw new \InvalidArgumentException(sprintf('Job "%s" does not exist', $job));
            }
        }

        $names = $this->getDependencyResolver()->resolveSingle($name);

        $this->executeActions($output, $names, $name, $job);
    }

    private function getDependencyResolver(): DependencyResolver
    {
        $dependencyResolver = new DependencyResolver();

        foreach ($this->actions->getActionDefinitions() as $action) {
            $dependencyResolver->addDependency($action->getName(), $action->getDependencies());
        }

        return $dependencyResolver;
    }

    private function executeActions(
        OutputInterface $output,
        array $actionNames,
        string $name,
        ?string $job = null
    ): void {
        foreach ($actionNames as $actionName) {
            $definition = $this->actions->get($actionName);
            $action = $this->actionFactory->fromDefinition($definition);

            if ($actionName === $name && null !== $job) {
                $action->executeJob($output, $job);
            } else {
                $action->execute($output);
            }

            $message = sprintf(' <fg=green>✔</> Action <info>%s</info> executed', $actionName);

            $output->writeln($message);
        }
    }
}
