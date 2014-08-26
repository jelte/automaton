<?php


namespace Automaton\Config\Definition;


use Symfony\Component\Config\Definition\BaseNode;
use Symfony\Component\Config\Definition\PrototypeNodeInterface;

class SpecialNode extends BaseNode implements PrototypeNodeInterface
{

    /**
     * Validates the type of a Node.
     *
     * @param mixed $value The value to validate
     */
    protected function validateType($value)
    {
        if (null === $value) {
            return;
        }
    }

    /**
     * Normalizes the value.
     *
     * @param mixed $value The value to normalize.
     *
     * @return mixed The normalized value
     */
    protected function normalizeValue($value)
    {
        return $value;
    }

    /**
     * Merges two values together.
     *
     * @param mixed $leftSide
     * @param mixed $rightSide
     *
     * @return mixed The merged value
     */
    protected function mergeValues($leftSide, $rightSide)
    {
        if ( !is_array($leftSide) ) {
            return $rightSide;
        }
        if ( !is_array($rightSide) ) {
            return $leftSide;
        }
        $result = array_merge($leftSide, $rightSide);
        if (array_keys($result) == range(0, count($result) - 1)) {
            return array_unique($result);
        }
        return $result;
    }

    /**
     * Finalizes a value.
     *
     * @param mixed $value The value to finalize
     *
     * @return mixed The finalized value
     */
    protected function finalizeValue($value)
    {
        return $value;
    }

    /**
     * Returns true when the node has a default value.
     *
     * @return bool    If the node has a default value
     *
     * @codeCoverageIgnore
     */
    public function hasDefaultValue()
    {
        return false;
    }

    /**
     * Returns the default value of the node.
     *
     * @return mixed The default value
     * @throws \RuntimeException if the node has no default value
     *
     * @codeCoverageIgnore
     */
    public function getDefaultValue()
    {
    }

    /**
     * Sets the name of the node.
     *
     * @param string $name The name of the node
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
