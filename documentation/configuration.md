---
layout: layout
title: Documentation - Configuration
---
## Configuration

PHP automaton uses Symfony's Dependency Injection Container to define its configuration.
So configuring PHP automaton is very simular to configuring Symfony.

~~~
imports:
  - { resource: src/automaton/Resources/configs/automaton.yml }

parameters:
  host: my.domain
  username: me

automaton:
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
    debug: automaton\Recipe\Common::debug
    deploy: automaton\Recipe\Common::deploy
    deploy:update_code: automaton\Recipe\Common::update_code
  <myPlugin>:
    _config: configs/myPlugin.<yml|xml>
~~~