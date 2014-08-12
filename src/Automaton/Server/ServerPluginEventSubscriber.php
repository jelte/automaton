<?php


namespace Automaton\Server;

use Automaton\Console\Command\Event\TaskCommandEvent;
use Automaton\Console\Command\Event\TaskEvent;
use Automaton\Plugin\AbstractPluginEventSubscriber;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ServerPluginEventSubscriber extends AbstractPluginEventSubscriber
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(ServerPlugin $plugin, EventDispatcherInterface $eventDispatcherInterface)
    {
        parent::__construct($plugin);
        $this->eventDispatcher = $eventDispatcherInterface;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'automaton.task_command.configure' => 'configureTaskCommand',
            'automaton.task.pre_run' => array('preTaskRun', 99),
            'automaton.task.run' => array('onRun',10)
        );
    }

    /**
     * @param TaskCommandEvent $event
     */
    public function configureTaskCommand(TaskCommandEvent $event)
    {
        $event->getCommand()->addOption('server', 's', InputOption::VALUE_OPTIONAL, 'Force to run on a specific server');
        $event->getCommand()->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry-Run');
    }

    /**
     * @param TaskEvent $event
     */
    public function preTaskRun(TaskEvent $event)
    {
        $environment = $event->getRuntimeEnvironment();
        $input = $environment->getInput();
        $servers = $this->plugin->all();

        if ( $input->hasOption('dry-run') ) {
            $output = $environment->getOutput();
            $servers = array_map(function($value) use ($output) {
               return new DryRunServer($value, $output);
            },$servers);
        }
        if ($input->hasOption('server') && $serverName = $input->getOption('server')) {
            $servers = array($serverName => $servers[$serverName]);
        }
        $environment->set('servers',$servers);
    }

    /**
     * @param TaskEvent $taskEvent
     */
    public function onRun(TaskEvent $taskEvent)
    {
        $environment = $taskEvent->getRuntimeEnvironment();
        $task = $taskEvent->getTask();
        $servers =  $environment->get('servers', array());
        foreach ( $servers as $server ) {
            $environment->set('server', $server);
            $this->eventDispatcher->dispatch('automaton.task.invoke', new TaskEvent($task, $environment));
        }
        $taskEvent->stopPropagation();
    }
}