---
layout: layout
title: Documentation
---
# Documentation
automaton is made to live without your project, not globally (although it is possible).
We wanted a tool that was kept constant with our project during the projects lifecycle, not with our system.

With that goal we set out to write a tool which would allow us to do just that and have it updated with composer.

## Including automaton in your application

~~~
composer require --dev jelte/php-automaton:~1.x
~~~

## Running PHP automaton

~~~
vendor/bin/automaton <task>
~~~

### with custom configuration
 
~~~
vendor/bin/automaton -c <path-to-configuration-file>
~~~

example:

~~~
vendor/bin/automaton -c vendor/automaton-schemes/config/default.yml
~~~

### profiling your tasks

~~~
vendor/bin/automaton --profile
~~~

### enable debugging

~~~
vendor/bin/automaton -vvv
~~~