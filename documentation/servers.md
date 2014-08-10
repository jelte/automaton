---
layout: layout
title: Documentation - Servers
---
# Servers

## Defining servers
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