<?php


namespace Automaton\Recipe\Annotation;


/**
 * @Annotation
 * Class Before
 * @package Automaton\Recipe\Annotation
 */
final class After implements Annotation
{
    public $task;

    public $priority = 0;
}
