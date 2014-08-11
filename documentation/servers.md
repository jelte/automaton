---
layout: layout
title: Documentation - Servers
---
## Servers

### CLI Options

When the ServerPlugin is loaded the option `--server` will be enabled. 
This allows you to run all tasks on a specific server.

~~~
vendor/bin/deployer <task> --server=<server-name>
~~~

### Defining servers
~~~
deployer:
  server:
    server-1: ssh://username:password@host:port/path
    server-2: username@host/~/path
    server-2:
        uri:
            scheme: ssh
            host: my-host
            login: username
            password: password
            port: 22
            path: /home/username/path
    server-3:
        uri:
            host: my-host
            login: username
            path: ~/path
        privateKey: ~/.ssh/id_rsa
~~~