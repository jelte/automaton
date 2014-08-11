---
layout: layout
title: Documentation - Stages
---
# Stages

## CLI Options

The Stages Plugin will add a `stage` argument.

this will change the command line to 

~~~
vendor/bin/deployer <stage> <task>
~~~

## Defining stages
~~~
deployer:
  stage:
    develop:
      servers: [server_1, server_2]
      options: {branch: develop}
    stage:
      servers: [server_3]
~~~