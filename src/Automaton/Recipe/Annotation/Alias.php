<?php


namespace Automaton\Recipe\Annotation;


/**
 * @Annotation
 * Class Before
 * @package Automaton\Recipe\Annotation
 */
final class Alias implements Annotation
{
    public $name;

    public $public = true;
}
