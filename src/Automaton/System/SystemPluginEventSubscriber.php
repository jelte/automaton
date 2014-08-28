<?php


namespace Automaton\System;


use Automaton\Console\Command\Event\TaskCommandEvent;
use Automaton\Console\Command\Event\TaskEvent;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SystemPluginEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var SystemInterface
     */
    protected $system;

    public function __construct(SystemInterface $system)
    {
        $this->system = $system;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'automaton.task_command.configure' => 'configureTaskCommand',
            'automaton.task.pre_run' => 'preTaskRun'
        );
    }

    /**
     * @param TaskCommandEvent $event
     */
    public function configureTaskCommand(TaskCommandEvent $event)
    {
        $event->getCommand()->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry-Run');
    }

    /**
     * @param TaskEvent $event
     */
    public function preTaskRun(TaskEvent $event)
    {
        $environment = $event->getRuntimeEnvironment();
        $input = $environment->getInput();

        $system = $this->system;
        if ( $input->getOption('dry-run') ) {
            $system = new DryRunSystem($environment->getOutput());
        }
        $system->setOutput($environment->getOutput());
        $environment->set('system', $system);
        $environment->set('filesystem', $system->getFilesystem());
    }
}
