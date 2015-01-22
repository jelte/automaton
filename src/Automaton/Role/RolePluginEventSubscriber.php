<?php


namespace Automaton\Role;

use Automaton\Console\Command\Event\InvokeEvent;
use Automaton\Console\Command\Event\TaskCommandEvent;
use Automaton\Console\Command\Event\TaskEvent;
use Automaton\Plugin\AbstractPluginEventSubscriber;
use Symfony\Component\Console\Input\InputOption;

class RolePluginEventSubscriber extends AbstractPluginEventSubscriber
{
    public function __construct(RolePlugin $plugin)
    {
        parent::__construct($plugin);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'automaton.task_command.configure' => 'configureTaskCommand',
            'automaton.task.pre_run' => array('preTaskRun', 99),
            'automaton.task.do_invoke' => array('doInvoke', 10)
        );
    }

    /**
     * @param TaskCommandEvent $event
     */
    public function configureTaskCommand(TaskCommandEvent $event)
    {
        $event->getCommand()->addOption('role', 'r', InputOption::VALUE_OPTIONAL, 'Run only task with a specific role');
    }

    /**
     * @param TaskEvent $event
     */
    public function preTaskRun(TaskEvent $event)
    {
        $environment = $event->getRuntimeEnvironment();
        $input = $environment->getInput();
        if ($role = $input->getOption('role')) {
            $environment->set('role', $input->getOption('role'));
        }
    }

    /**
     * @param InvokeEvent $invokeEvent
     */
    public function doInvoke(InvokeEvent $invokeEvent)
    {
        $environment = $invokeEvent->getRuntimeEnvironment();
        $task = $invokeEvent->getTask();
        $server = $environment->get('server');

       // $invokeEvent->stopPropagation();
    }
}
