<?php


namespace Deployer\Task;


interface AliasInterface extends TaskInterface {
    public function getOriginal();
} 