<?php


namespace Automaton\Task;


interface TaskInterface
{
    public function getName();

    public function getDescription();

    public function before(TaskInterface $task);

    public function getBefore();

    public function after(TaskInterface $task);

    public function getAfter();
} 