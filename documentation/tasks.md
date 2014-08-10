---
layout: layout
title: Documentation - Tasks
---
# Tasks
The most crucial Plugin of deploy. Tasks can be any valid callable

## Defining tasks

~~~
deployer:
  task:
    init: Deployer\Recipe\Common::init
    deploy: 
        - init
        - deploy:update_code
    deploy:update_code: Deployer\Recipe\Common::updateCode
    deploy:rollback: Deployer\Recipe\Common::rollback
~~~