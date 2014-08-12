---
layout: layout
title: Documentation - Servers
---
## Servers

Allows you to execute tasks on remote servers.

### CLI Options

The ServerPlugin enables following options:

- **server**: Run tasks on a specific server 
- **dry-run**: Run tasks without actually communicating to the server.

#### Run tasks on a specific server 

The `--server=<server-name>` option allows you to run all tasks on a specific server.

~~~
vendor/bin/automaton <task> --server=<server-name>
~~~

#### Run in dry-run mode

The `--dry-run` option runs all tasks without communicating to the server, but it will output all action and commands to the console.

~~~
vendor/bin/automaton <task> --dry-run
~~~

### Defining servers
~~~
automaton:
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