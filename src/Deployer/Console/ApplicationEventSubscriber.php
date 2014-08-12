<?php


namespace Deployer\Console;


use Deployer\Console\Command\Event\ApplicationEvent;
use Deployer\Console\Command\RunTaskCommand;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ApplicationEventSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array('deployer.load' => 'onLoad');
    }

    public function onLoad(ApplicationEvent $event)
    {
        $eventDispatcher = $event->getApplication()->getContainer()->get('event_dispatcher');
        foreach ($event->getApplication()->getContainer()->get('deployer')->all('task') as $taskName => $task) {
            $event->getApplication()->add(new RunTaskCommand($task, $eventDispatcher));
        }
    }
}