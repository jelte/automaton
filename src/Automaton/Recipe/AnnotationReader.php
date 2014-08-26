<?php


namespace Automaton\Recipe;


class AnnotationReader extends \Doctrine\Common\Annotations\AnnotationReader
{
    /**
     * {@inheritDoc}
     */
    public function getMethodAnnotations(\ReflectionMethod $method, $annotationName = null)
    {
        $allAnnotations = parent::getMethodAnnotations($method);
        if ( null === $annotationName ) {
            return $allAnnotations;
        }
        $annotations = array();
        foreach ($allAnnotations as $annotation) {
            if ($annotation instanceof $annotationName) {
                $annotations[] = $annotation;
            }
        }
        return $annotations;
    }
}
