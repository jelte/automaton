---
layout: layout
title: Documentation - Stages
---
# Stages

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