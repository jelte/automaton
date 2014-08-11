---
layout: layout
title: Documentation - Stages
---
## Stages

### CLI Options

The StagesPlugin will add a `stage` argument. 

*Remember that plugins are lazy-loaded, so if no stages are defined the stage argument will not be available*

this will change the command line to 

~~~
vendor/bin/deployer <stage> <task>
~~~

### Defining stages
~~~
deployer:
  stage:
    develop:
      servers: [server_1, server_2]
      options: {branch: develop}
    stage:
      servers: [server_3]
~~~