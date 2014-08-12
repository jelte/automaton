<?php


namespace Automaton\Config;

use Automaton\DependencyInjection\AutomatonCompilerPass;
use Automaton\DependencyInjection\AutomatonExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;

class ConfigurationLoader {


    protected $cwd;

    protected $container;

    public function __construct(ContainerBuilder $container = null, $cwd = null)
    {
        $this->cwd = null == $cwd?getcwd():$cwd;
        $this->container = null === $container?new ContainerBuilder():$container;
        $this->addCompilerPass();
        $this->registerExtensions();
    }

    protected function registerExtensions()
    {
        $resolver = $this->initLoaderResolver(new FileLocator($this->cwd));
        $this->container->set('resolver', $resolver);
        $this->container->registerExtension(new AutomatonExtension($resolver));
    }

    protected function addCompilerPass()
    {
        $this->container->addCompilerPass(new AutomatonCompilerPass());
        $this->container->addCompilerPass(new RegisterListenersPass());
    }

    protected function initLoaderResolver(FileLocatorInterface $fileLocator)
    {
        return new LoaderResolver(array(
            new YamlFileLoader($this->container, $fileLocator),
            new XmlFileLoader($this->container, $fileLocator)
        ));
    }

    public function load($config)
    {
        $this->container->get('resolver')->resolve($config)->load($config);

        $this->container->compile();

        return $this->container;
    }
} 