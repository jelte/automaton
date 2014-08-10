<?php


namespace Deployer\Server;


use Deployer\Console\Command\Event\RunnerEvent;
use Deployer\Console\Command\Event\TaskCommandEvent;
use Deployer\Plugin\AbstractPluginEventSubscriber;
use Symfony\Component\Console\Input\InputOption;

class ServerPluginEventSubscriber extends AbstractPluginEventSubscriber
{
    public function __construct(ServerPlugin $plugin)
    {
        parent::__construct($plugin);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'deployer.task_command.configure' => 'onTaskCommandConfigure',
            'deployer.runner.pre_run' => array('onRunnerPreRun', 99),
            'deployer.runner.post_run' => 'onRunnerPostRun',
        );
    }

    /**
     * @internal
     * @param TaskCommandEvent $event
     */
    public function onTaskCommandConfigure(TaskCommandEvent $event)
    {
        $event->getCommand()->addOption('server', 's', InputOption::VALUE_OPTIONAL, 'Force to run on a specific server');
    }

    /**
     * @internal
     * @param RunnerEvent $event
     */
    public function onRunnerPreRun(RunnerEvent $event)
    {
        $event->getRunner()->setServers($this->plugin->all());
        if ($event->getInput()->hasOption('server') && $serverName = $event->getInput()->getOption('server')) {
            $event->getRunner()->setServers(array($serverName => $this->plugin->get($serverName)));
        }
    }

    /**
     * @internal
     * @param RunnerEvent $event
     */
    public function onRunnerPostRun(RunnerEvent $event)
    {
        $event->getRunner()->setServers($this->plugin->all());
    }
}