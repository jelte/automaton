<?php


namespace Deployer\Stage;


use Deployer\Console\Command\Event\RunnerEvent;
use Deployer\Console\Command\Event\TaskCommandEvent;
use Deployer\Plugin\AbstractPluginEventSubscriber;
use Symfony\Component\Console\Input\InputArgument;

class StagePluginEventSubscriber extends AbstractPluginEventSubscriber
{
    public function __construct(StagePlugin $plugin)
    {
        parent::__construct($plugin);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'deployer.task_command.configure' => array('onTaskCommandConfigure', 99),
            'deployer.runner.pre_run' => 'onRunnerPreRun',
            'deployer.runner.post_run' => 'onRunnerPostRun',
        );
    }

    /**
     * @internal
     * @param TaskCommandEvent $event
     */
    public function onTaskCommandConfigure(TaskCommandEvent $event)
    {
        $event->getCommand()->addArgument('stage', InputArgument::OPTIONAL, 'Run commands on a specific set of servers', $this->plugin->getDefaultInstance());
    }

    /**
     * @internal
     * @param RunnerEvent $event
     */
    public function onRunnerPreRun(RunnerEvent $event)
    {
        if ( $event->getInput()->hasArgument('stage') && $stageName = $event->getInput()->getArgument('stage')) {
            /** @var Stage $stage */
            $stage = $this->plugin->get($stageName);

            $stageServers = $stage->getServers();
            $servers = [];
            foreach ( $event->getRunner()->getServers() as $serverName => $server ) {
                if ( in_array($serverName, $stageServers) ) {
                    $servers[$serverName] = $server;
                }
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