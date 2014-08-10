<?php


namespace Deployer\Plugin;


interface PluginInterface {
    public function get($name);

    public function all();
} 