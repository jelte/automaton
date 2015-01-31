<?php


namespace Automaton\Task;


interface ExecutableTaskInterface extends TaskInterface {

    /**
     * @return \ReflectionFunction|\ReflectionMethod|array
     */
    public function getCallable();
}
