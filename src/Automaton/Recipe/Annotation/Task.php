<?php


namespace Automaton\Recipe\Annotation;


/**
 * @Annotation
 *
 * Class Task
 * @package Automaton\Recipe\Annotation
 */
final class Task implements Annotation
{
    public $description;
    public $public = false;
}
