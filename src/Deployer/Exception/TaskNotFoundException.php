<?php


namespace Deployer\Exception;


class TaskNotFoundException extends \Exception
{
    protected $message = 'Task "%s" not defined.';

    public function __construct($task, $code = 0, \Exception $previous = null)
    {
        parent::__construct(sprintf($this->message, $task), $code, $previous);
    }
} 