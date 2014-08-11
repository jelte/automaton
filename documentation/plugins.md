---
layout: layout
title: Documentation - Plugins
---
## Plugins

Deployer is completely made up of plugins.
Plugins may allow instances to be defined through configuration, cli options or arguments to be added, ...

default plugins:

- **server**: Allows servers to be defined and adds the --server option 
- **stage**: Allows stages to be defined and adds the "stage" argument
- **task**: Allows tasks to be defined
- **runner**: Has a single instance of the Runner which will run the tasks.

### Creating a new Plugin

Creating a new plugin is simple.
You just have to create the Plugin class, a configuration file and a subscriber.

### The Plugin class

The plugin class is responsible for exposing methods in the API.
the following will expose `Deployer->my($name)` and register a new `My` instance.

~~~
class MyPlugin extends AbstractPlugin
{
    public function my($name[, <your params>])
    {
        return $this->registerInstance($name, new My($name, <your params>));
    }
}
~~~

every public function will be exposed unless it is annotated with `@internal`.

~~~
class MyPlugin extends AbstractPlugin
{
    private $default;

    public function my($name[, <your params>], $default = false)
    {
        $instance = $this->registerInstance($name, new My($name, <your params>));
        if ( $default ) $default = $instance;
        return $instance;
    }
    
    /**
     * @internal
     **/
    public function getDefault()
    {
        return $this->default;
    }
}
~~~


### Configuring the plugin

This is done easily by defining a configuration file.

~~~
services:
  deployer.plugin.my:
    class: MyNamespace\MyPlugin
~~~

note: Plugins are lazy-loaded, see "loading the plugin"


### Making use of your plugin

At the moment there are 3 points in the execution that can be influenced by a plugin:

- **deployer.task_command.configure** configuring a command
- **deployer.runner.pre_run** Before running a command
- **deployer.runner.post_run** After running a command

When these actions happen a event is dispatched, by making use of a EventSubscriber it is possible to create some impact.

~~~
class ServerEventSubscriber extends AbstractPluginEventSubscriber
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'deployer.task_command.configure' => 'onTaskCommandConfigure',
            'deployer.runner.pre_run' => array('onRunnerPreRun', 99),
            'deployer.runner.post_run' => 'onRunnerPostRun',
        );
    }

    ...
}
~~~

**onTaskCommandConfigure** can make changes to the TaskCommand. This is generally used to add input options or arguments

~~~
    public function onTaskCommandConfigure(TaskCommandEvent $event)
    {
        $event->getCommand()->addOption('server', 's', InputOption::VALUE_OPTIONAL, 'Force a specific server');
    }
~~~
   
**onRunnerPreRun** Will be executed before the Runner is executed. 

Here we assign all servers to the runner unless the options --server is specified
   
~~~
    public function onRunnerPreRun(RunnerEvent $event)
    {
        $event->getRunner()->setServers($this->plugin->all());
        if ($event->getInput()->hasOption('server') && $serverName = $event->getInput()->getOption('server')) {
            $event->getRunner()->setServers(array($serverName => $this->plugin->get($serverName)));
        }
    }
~~~
  
**onRunnerPostRun** Will be executed after the Runner is executed. 

Here we simply restore all servers to revert the --server option

~~~
    public function onRunnerPostRun(RunnerEvent $event)
    {
        $event->getRunner()->setServers($this->plugin->all());
    }
~~~


### Loading your new Plugin

add the following to your deploy configuration file under `deployer`

~~~
deployer:
  my:
    _config: <some-location>/my.yml
    <instance-name>: <instance-string-param>
~~~

when the configuration of the Deployer is loaded it will load the configuration file defined in `_config` 
and register all instances defined under it.