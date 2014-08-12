<?php


namespace Automaton\Console;


use Automaton\Console\Command\Event\ApplicationEvent;
use Automaton\Console\Command\RunTaskCommand;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ApplicationEventSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array('automaton.load' => 'onLoad');
    }

    public function onLoad(ApplicationEvent $event)
    {
        $eventDispatcher = $event->getApplication()->getContainer()->get('event_dispatcher');
        foreach ($event->getApplication()->getContainer()->get('automaton')->all('task') as $taskName => $task) {
            $event->getApplication()->add(new RunTaskCommand($task, $eventDispatcher));
        }
    }
}