---
layout: layout
title: Documentation - Tasks
---
## Tasks

A task can be any callable function:

- object method: `array($object, 'method-name')`
- static class method: `MyClass::methodName`
- function name: `functionName` *not recommended*

### Creating new Tasks

Any valid method or function can be a Task.

~~~
function mytask([<Class> $<environment-value>])
{
    ... your code ...
}
~~~

*note: Using functions is not recommended, but it is possible. It is better to use a static method.*

Through reflection parameters will be added to your function from the RuntimeEnvironment. 

So to get the current server:

~~~
function mytask(ServerInterface $server)
{
    ... your code ...
}
~~~


#### Configuration

~~~
automaton:
  task:
    init: automaton\Recipe\Common::init
~~~

*note: Method calls haven't been tested yet*

### Grouping Tasks


#### Configuration

~~~
automaton:
  task:
    deploy_server: deploy
~~~

### Alias

An alias is a complete copy of a task including its Before and After tasks, but allows you to add additional Before and After tasks.

#### Configuration

~~~
automaton:
  alias:
    deploy_server: deploy
~~~
