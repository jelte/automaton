<?php


namespace Deployer\DependencyInjection;


use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class DeployerExtension extends Extension
{
    /**
     * @var LoaderResolverInterface
     */
    protected $loaderResolver;

    /**
     * @var Processor
     */
    protected $configProcessor;

    /**
     * @var ConfigurationInterface
     */
    protected $configuration;

    public function __construct(LoaderResolverInterface $loaderResolver, Processor $configProcessor = null, ConfigurationInterface $configuration = null)
    {
        $this->loaderResolver = $loaderResolver;
        $this->configProcessor = null === $configProcessor?new Processor():$configProcessor;
        $this->configuration = null === $configuration?new Configuration():$configuration;
    }

    /**
     * Loads a specific configuration.
     *
     * @param array $configs An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @api
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->configProcessor->processConfiguration($this->configuration, $configs);

        foreach (array_keys($config) as $plugin) {
            $file = $container->getParameter('deployer.plugins.config_path').DIRECTORY_SEPARATOR.$plugin . '.yml';
            if ( isset($config[$plugin]['_config']) ) {
                $file = $config[$plugin]['_config'];
            }
            if ( file_exists($file) ) {
                $this->loaderResolver->resolve($file)->load($file);
            }
        }
    }
}