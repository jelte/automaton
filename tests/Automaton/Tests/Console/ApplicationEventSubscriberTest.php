<?php


namespace Automaton\Tests\Console;


use Automaton\Console\ApplicationEventSubscriber;

class ApplicationEventSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ApplicationEventSubscriber
     */
    protected $eventSubscriber;

    public function setUp()
    {
        $this->eventSubscriber = new ApplicationEventSubscriber();
    }

    /**
     * @test
     */
    public function hasSubscribedEvents()
    {
        $this->assertInternalType('array',ApplicationEventSubscriber::getSubscribedEvents());
        $this->assertCount(1,ApplicationEventSubscriber::getSubscribedEvents());
    }

    /**
     * @test
     */
    public function onLoad()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $application = $this->getMock('Automaton\Console\Application');
        $event = $this->getMock('Automaton\Console\Command\Event\ApplicationEvent', array(), array(), '', false);
        $event->expects($this->any())->method('getApplication')->willReturn($application);
        $application->expects($this->any())->method('getContainer')->willReturn($container);
        $eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $automaton = $this->getMock('Automaton\Automaton', array(), array(), '', false);
        $task = $this->getMock('Automaton\Task\TaskInterface');
        $task->expects($this->once())->method('getName')->willReturn('test');
        $automaton->expects($this->once())->method('all')->willReturn(array('test' => $task));
        $container->expects($this->any())->method('get')->withConsecutive(
            array($this->equalTo('event_dispatcher')),
            array($this->equalTo('automaton'))
        )->willReturnOnConsecutiveCalls(
            $eventDispatcher, $automaton
        );
        $this->eventSubscriber->onLoad($event);
    }

} 