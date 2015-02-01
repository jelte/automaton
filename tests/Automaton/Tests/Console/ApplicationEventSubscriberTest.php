<?php


namespace Automaton\Tests\Console;


use Automaton\Automaton;
use Automaton\Console\Application;
use Automaton\Console\ApplicationEventSubscriber;
use Automaton\Console\Command\Event\ApplicationEvent;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class ApplicationEventSubscriberTest extends ProphecyTestCase
{
    /**
     * @var ApplicationEventSubscriber
     */
    protected $eventSubscriber;

    public function setUp()
    {
        parent::setUp();
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
        $container = $this->prophesize('Symfony\Component\DependencyInjection\ContainerBuilder');
        $eventDispatcher = $this->prophesize('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $automaton = $this->prophesize('Automaton\Automaton');
        $container->get('event_dispatcher')->willReturn($eventDispatcher->reveal());
        $container->getParameterBag()->willReturn(new ParameterBag());
        $container->get('automaton')->willReturn($automaton->reveal());
        $application = $this->prophesize('Automaton\Console\Application');
        $application->getContainer()->willReturn($container);
        $application->add(Argument::type('Automaton\Console\Command\RunTaskCommand'))->shouldBeCalled();

        $task = $this->prophesize('Automaton\Task\TaskInterface');
        $task->getName()->willReturn('test');
        $task->isPublic()->willReturn(true);
        $automaton->all('task')->willReturn(array('test' => $task));

        $event = new ApplicationEvent(
            $application->reveal(),
            $this->prophesize('Symfony\Component\Console\Input\InputInterface')->reveal(),
            $this->prophesize('Symfony\Component\Console\Output\OutputInterface')->reveal()
        );


        $this->eventSubscriber->onLoad($event);
        $this->assertTrue(true);
    }

} 