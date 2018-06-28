<?php
/**
 * Created by PhpStorm.
 * User: igroc
 * Date: 27.06.2018
 * Time: 9:50
 */

/**
 * Class Node
 * node for binary tree
 * @property string $value
 * @property Node $left | null
 * @property Node $right | null
 */
class Node
{
    public $value;
    public $left;
    public $right;

    /**
     * Node constructor.
     * @param $value| null
     * @param Node $left | null
     * @param Node $right | null
     */
    public function __construct($value = null, Node $left = null, Node $right = null )
    {
        $this->value = $value;
        $this->right = $right;
        $this->left = $left;
    }
}