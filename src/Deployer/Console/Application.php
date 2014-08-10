<?php


namespace Deployer\Console;

use Deployer\Config\ConfigurationLoader;
use Deployer\Console\Command\Event\ApplicationEvent;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Application extends BaseApplication
{
    protected $configuration;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN', ConfigurationLoader $configuration = null)
    {
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
        if ($input->hasParameterOption(array('--profile'))) {
            $startTime = microtime(true);
        }

        $this->container = $this->configuration->load($input->getParameterOption(array('--config', '-c'), 'deploy.yml'));

        $this->setDispatcher($this->container->get('event_dispatcher'));

        $this->container->get('event_dispatcher')->dispatch('deployer.load', new ApplicationEvent($this, $input, $output));

        $result = parent::doRun($input, $output);

        if (isset($startTime)) {
            $output->writeln('<info>Memory usage: ' . round(memory_get_usage() / 1024 / 1024, 2) . 'MB (peak: ' . round(memory_get_peak_usage() / 1024 / 1024, 2) . 'MB), time: ' . round(microtime(true) - $startTime, 2) . 's');
        }

        return $result;
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