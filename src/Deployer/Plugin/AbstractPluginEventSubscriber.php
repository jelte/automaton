<?php


namespace Deployer\Plugin;


use Deployer\Console\Command\Event\RunnerEvent;
use Deployer\Console\Command\Event\TaskCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class AbstractPluginEventSubscriber implements EventSubscriberInterface
{

    protected $plugin;

    protected function __construct(PluginInterface $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @internal
     * @param TaskCommandEvent $event
     */
    abstract public function onTaskCommandConfigure(TaskCommandEvent $event);

    /**
     * @internal
     * @param RunnerEvent $event
     */
    abstract public function onRunnerPreRun(RunnerEvent $event);

    /**
     * @internal
     * @param RunnerEvent $event
     */
    abstract public function onRunnerPostRun(RunnerEvent $event);
} 