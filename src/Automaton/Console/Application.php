<?php


namespace Automaton\Console;

use Automaton\Config\ConfigurationLoader;
use Automaton\Console\Command\Event\ApplicationEvent;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class Application extends BaseApplication
{
    protected $stopwatch;
    protected $configuration;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN', ConfigurationLoader $configuration = null)
    {
        $this->stopwatch = new Stopwatch();
        parent::__construct($name, $version);
        $this->configuration = null == $configuration?new ConfigurationLoader():$configuration;
    }

    public function getContainer()
    {
        return $this->container;
    }

    /**
     * {@inheritDoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->container = $this->configuration->load($input->getParameterOption(array('--config', '-c'), 'deploy.yml'), $this->stopwatch, $input->hasParameterOption(array('--profile')));

        $eventDispatcher = $this->container->get('event_dispatcher');

        $this->setDispatcher($eventDispatcher);

        /** @var \Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher $eventDispatcher */
        $eventDispatcher->dispatch('automaton.load', new ApplicationEvent($this, $input, $output));
        $eventDispatcher->dispatch('automaton.post_load', new ApplicationEvent($this, $input, $output));

        return parent::doRun($input, $output);
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultInputDefinition()
    {
        $definition = parent::getDefaultInputDefinition();
        $definition->addOption(new InputOption('profile', null, InputOption::VALUE_NONE, 'Display timing and memory usage information'));
        $definition->addOption(new InputOption('config', 'c', InputOption::VALUE_OPTIONAL, 'If specified, use the given directory as working directory.', 'deploy.yml'));

        return $definition;
    }
}
