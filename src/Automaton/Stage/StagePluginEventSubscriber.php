<?php


namespace Automaton\Stage;


use Automaton\Console\Command\Event\RunnerEvent;
use Automaton\Console\Command\Event\TaskCommandEvent;
use Automaton\Console\Command\Event\TaskEvent;
use Automaton\Plugin\AbstractPluginEventSubscriber;
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
            'automaton.task_command.configure' => array('configureTaskCommand', 99),
            'automaton.task.pre_run' => 'preTaskRun'
        );
    }

    /**
     * @param TaskCommandEvent $event
     */
    public function configureTaskCommand(TaskCommandEvent $event)
    {
        $event->getCommand()->addArgument('stage', InputArgument::OPTIONAL, 'Run commands on a specific set of servers', $this->plugin->getDefaultInstance());
    }

    /**
     * @param TaskEvent $event
     */
    public function preTaskRun(TaskEvent $event)
    {
        $environment = $event->getRuntimeEnvironment();
        $input = $environment->getInput();
        if ( $input->hasArgument('stage') && $stageName = $input->getArgument('stage')) {
            /** @var Stage $stage */
            $stage = $this->plugin->get($stageName);

            $stageServers = $stage->getServers();
            $servers = [];
            foreach ( $environment->get('servers', array()) as $serverName => $server ) {
                if ( in_array($serverName, $stageServers) ) {
                    $servers[$serverName] = $server;
                }
            }

            $environment->set('stage', $stage);
            $environment->set('servers', $servers);
        }
    }

}