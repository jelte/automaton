---
layout: layout
title: Documentation - Configuration
---
## Configuration

PHP Deployer uses Symfony's Dependency Injection Container to define its configuration.
So configuring PHP Deployer is very simular to configuring Symfony.

~~~
imports:
  - { resource: src/Deployer/Resources/configs/deployer.yml }

parameters:
  host: my.domain
  username: me

deployer:
  server:
    server_1: %username%@%myhost%
    server_2:
      uri:
          host: %myhost%
          login: %username%
  stage:
    develop:
      servers: [server_1, server_2]
      options: {branch: develop}
    stage:
      servers: [server_1]
  task:
    debug: Deployer\Recipe\Common::debug
    deploy: Deployer\Recipe\Common::deploy
    deploy:update_code: Deployer\Recipe\Common::update_code
  <myPlugin>:
    _config: configs/myPlugin.<yml|xml>
~~~