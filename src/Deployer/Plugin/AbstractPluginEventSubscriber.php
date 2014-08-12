<?php


namespace Deployer\Plugin;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class AbstractPluginEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var PluginInterfaces
     */
    protected $plugin;

    protected function __construct(PluginInterface $plugin)
    {
        $this->plugin = $plugin;
    }
} 