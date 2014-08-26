<?php


namespace Automaton\Task;


interface ExecutableTaskInterface extends TaskInterface {

    /**
     * @return \ReflectionFunction|\ReflectionMethod
     */
    public function getCallable();
}
