<?php


namespace Deployer\Task;


interface GroupTaskInterface extends TaskInterface {
    public function getTasks();
} 