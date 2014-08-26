<?php


namespace Automaton\Plugin;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class AbstractPluginEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var PluginInterface
     */
    protected $plugin;

    protected function __construct(PluginInterface $plugin)
    {
        $this->plugin = $plugin;
    }
}
