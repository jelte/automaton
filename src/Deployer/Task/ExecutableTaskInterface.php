<?php


namespace Deployer\Task;


interface ExecutableTaskInterface extends TaskInterface {

    /**
     * @return \ReflectionFunction|\ReflectionMethod
     */
    public function getCallable();
} 