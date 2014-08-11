---
layout: layout
title: Documentation
---
# Documentation


## Running PHP Deployer

~~~
vendor/bin/deployer <task>
~~~

### with custom configuration
 
~~~
vendor/bin/deployer -c <path-to-configuration-file>
~~~

example:

~~~
vendor/bin/deployer -c vendor/deployer-schemes/config/default.yml
~~~

### profiling your tasks

~~~
vendor/bin/deployer --profile
~~~

### enable debugging

~~~
vendor/bin/deployer -vvv
~~~