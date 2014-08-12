---
layout: layout
title: Documentation - Stages
---
## Stages

Allows you to define multiple stages ( group of servers ) for your deployment.

### CLI Options

The ServerPlugin enables following arguments:

- **stage**: Run tasks on a specific server

#### Running with the StagePlugin enabled

*Remember that plugins are lazy-loaded, so if no stages are defined the stage argument will not be available.*

*If stages have been defined, the `stage` argument is required*

this will change the command line to 

~~~
vendor/bin/automaton <stage> <task>
~~~

### Defining stages
~~~
automaton:
  stage:
    develop:
      servers: [server_1, server_2]
      options: {branch: develop}
    stage:
      servers: [server_3]
~~~