<?php


namespace Deployer\Runner;


use Deployer\Console\Command\Event\RunnerEvent;
use Deployer\Console\Command\Event\TaskCommandEvent;
use Deployer\Plugin\AbstractPluginEventSubscriber;
use Deployer\Server\DryRunServer;
use Symfony\Component\Console\Input\InputOption;

class RunnerPluginEventSubscriber extends AbstractPluginEventSubscriber {

    public function __construct(RunnerPlugin $plugin)
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
        $event->getCommand()->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry-Run');
    }

    /**
     * @internal
     * @param RunnerEvent $event
     */
    public function onRunnerPreRun(RunnerEvent $event)
    {
        if ( $event->getInput()->getOption('dry-run')) {
            $servers = [];
            foreach ( $event->getRunner()->getServers() as $serverName => $server ) {
                $servers[$serverName] = new DryRunServer($server, $event->getOutput());
            }
            $event->getRunner()->setServers($servers);
        }
    }

    /**
     * @internal
     * @param RunnerEvent $event
     */
    public function onRunnerPostRun(RunnerEvent $event)
    {
    }
}