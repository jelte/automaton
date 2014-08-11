---
layout: layout
title: Documentation - Tasks
---
# Tasks

A task can be any callable function:

- object method: `array($object, 'method-name')`
- static class method: `MyClass::methodName`
- function name: `functionName` *not recommended*

## Creating new Tasks

Any valid method or function can be a Task.

~~~
function mytask(InputInterface $input, OutputInterface $output, ServerInterface $server)
{
    ... your code ...
}
~~~

*note: Using functions is not recommended, but it is possible. It is better to use a static method.*

### Configuration

~~~
deployer:
  task:
    init: Deployer\Recipe\Common::init
~~~

*note: Method calls haven't been tested yet*

## Grouping Tasks


### Configuration

~~~
deployer:
  task:
    deploy_server: deploy
~~~

## Alias

An alias is a complete copy of a task including its Before and After tasks, but allows you to add additional Before and After tasks.

### Configuration

~~~
deployer:
  task:
    deploy_server: deploy
~~~
