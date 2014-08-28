<?php


namespace Automaton\Repository;


use Automaton\Console\Command\Event\RunnerEvent;
use Automaton\Console\Command\Event\TaskCommandEvent;
use Automaton\Console\Command\Event\TaskEvent;
use Automaton\Plugin\AbstractPluginEventSubscriber;
use Automaton\Repository\RepositoryPlugin;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RepositoryPluginEventSubscriber extends AbstractPluginEventSubscriber
{
    public function __construct(RepositoryPlugin $plugin)
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
        $event->getCommand()->addOption('branch', 'b', InputOption::VALUE_REQUIRED, 'Deploy a specific branch', $this->plugin->getBranch());
    }

    /**
     * @param TaskEvent $event
     */
    public function preTaskRun(TaskEvent $event)
    {
        $environment = $event->getRuntimeEnvironment();
        $input = $environment->getInput();

        $environment->set('repository', $this->plugin->getRepository());
        $environment->set('branch', $input->getOption('branch'));
        $environment->set('excludes', $this->plugin->getExcludes());
    }
}
