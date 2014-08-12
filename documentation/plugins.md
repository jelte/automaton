---
layout: layout
title: Documentation - Plugins
---
## Plugins

automaton is completely made up of plugins.
Plugins may allow instances to be defined through configuration, cli options or arguments to be added, ...

default plugins:

- **server**: Allows servers to be defined and adds the --server and --dry-run option 
- **stage**: Allows stages to be defined and adds the "stage" argument
- **task**: Allows tasks to be defined

### Creating a new Plugin

Creating a new plugin is simple.
You just have to create the Plugin class, a configuration file and a subscriber.

### The Plugin class

The plugin class is responsible for exposing methods in the API.
the following will expose `automaton->my($name)` and register a new `My` instance.

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
  automaton.plugin.my:
    class: MyNamespace\MyPlugin
~~~

note: Plugins are lazy-loaded, see "loading the plugin"


### Making use of your plugin

At the moment there are 3 points in the execution that can be influenced by a plugin:

- **automaton.task_command.configure** configuring a command
- **automaton.task.pre_run** Before running a command
- **automaton.task.run** While Running the task
- **automaton.task.post_run** After running a command

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
            'automaton.task_command.configure' => 'configureTaskCommand',
            'automaton.task.pre_run' => 'preRun',
            'automaton.task.run' => array('onRun', 20),
            'automaton.task.post_run' => 'postRun',
        );
    }

    ...
}
~~~

**configureTaskCommand** can make changes to the TaskCommand. This is generally used to add input options or arguments

~~~
    public function configureTaskCommand(TaskCommandEvent $event)
    {
        $event->getCommand()->addOption('server', 's', InputOption::VALUE_OPTIONAL, 'Force to run on a specific server');
        $event->getCommand()->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry-Run');
    }
~~~
   
**preRun** Will be executed before the task is executed. 

During the pre-run you prepare the RuntimeEnvironment with all parameters that generally change due to input options and arguments.
   
~~~
    public function preRun(TaskEvent $event)
    {
        $environment = $event->getRuntimeEnvironment();
        $input = $environment->getInput();
        $servers = $this->plugin->all();

        if ( $input->hasOption('dry-run') ) {
            $output = $environment->getOutput();
            $servers = array_map(function($value) use ($output) {
               return new DryRunServer($value, $output);
            },$servers);
        }
        if ($input->hasOption('server') && $serverName = $input->getOption('server')) {
            $servers = array($serverName => $servers[$serverName]);
        }
        $environment->set('servers',$servers);
    }
~~~

**onRun** On Run is meant to allow you to change how tasks are processed. 

For example in the ServerPlugin tasks are invoked for each server, rather than just once.

*Note the `stopPropagation()` call at the end. We don't want the task to be executed again.

~~~
  public function onRun(TaskEvent $event)
  {
        $environment = $taskEvent->getRuntimeEnvironment();
        $task = $taskEvent->getTask();
        $servers =  $environment->get('servers', array());
        foreach ( $servers as $server ) {
            $environment->set('server', $server);
            $this->eventDispatcher->dispatch('automaton.task.invoke', new TaskEvent($task, $environment));
        }
        $taskEvent->stopPropagation();
  }
~~~

**postRun** Will be executed after the task is executed. 

~~~
    public function postRun(TaskEvent $event)
    {
        ...
    }
~~~


### Loading your new Plugin

add the following to your deploy configuration file under `automaton`

~~~
automaton:
  my:
    _config: <some-location>/my.yml
    <instance-name>: <instance-string-param>
~~~

when the configuration of the automaton is loaded it will load the configuration file defined in `_config` 
and register all instances defined under it.