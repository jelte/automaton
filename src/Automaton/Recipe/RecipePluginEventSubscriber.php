<?php


namespace Automaton\Recipe;

use Automaton\Console\Command\Event\ApplicationEvent;
use Automaton\Console\Command\RunTaskCommand;
use Automaton\Plugin\AbstractPluginEventSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RecipePluginEventSubscriber extends AbstractPluginEventSubscriber
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(RecipePlugin $plugin, EventDispatcherInterface $eventDispatcherInterface)
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
            'automaton.post_load' => 'loadRecipes'
        );
    }

    /**
     * @param ApplicationEvent $event
     */
    public function loadRecipes(ApplicationEvent $event)
    {
        $application = $event->getApplication();
        $eventDispatcher = $application->getContainer()->get('event_dispatcher');
        $automaton = $application->getContainer()->get('automaton');
        foreach ($this->plugin->all() as $recipe) {
            foreach ($recipe->tasks() as $task) {
                call_user_func_array(array($automaton, 'task'), array($task->getName(), $task));
                if ($task->isPublic()) {
                    $application->add(new RunTaskCommand($task, $eventDispatcher, $application->getContainer()->getParameterBag()));
                }
            }
            foreach ($recipe->befores() as $before) {
                call_user_func_array(array($automaton, 'before'), $before);
            }
            foreach ($recipe->afters() as $after) {
                call_user_func_array(array($automaton, 'after'), $after);
            }
        }
    }
}
