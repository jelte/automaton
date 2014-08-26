<?php


namespace Automaton\Recipe;

use Automaton\Console\Command\Event\ApplicationEvent;
use Automaton\Console\Command\RunTaskCommand;
use Automaton\Plugin\AbstractPluginEventSubscriber;
use Automaton\Recipe\RecipePlugin;
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
        foreach ($this->plugin->all() as $recipe) {
            foreach ( $recipe->load() as $task ) {
                $application->add(new RunTaskCommand($task, $eventDispatcher));
            }
        }
    }
}
