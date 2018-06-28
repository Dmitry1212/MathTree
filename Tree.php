<?php
/**
 * Created by PhpStorm.
 * User: igroc
 * Date: 27.06.2018
 * Time: 9:48
 */


/**
 * Class Tree
 * binary Tree for calculation math expressions
 * @property SplStack $stack
 * @property Node $root
 * @property float|int|string $x
 * @property float|int|string $y
 * @property float|int|string $z
 * @property array $vars;
 *
 */
class Tree
{
    private $stack;
    private $root;
    private $x, $y, $z;
    private $vars = [
        'x', 'y', 'z'
    ];

    /**
     * Tree constructor.
     */
    public function __construct()
    {
        $this->stack = new SplStack();
    }

    /**
     * @param int $x
     * @param int $y
     * @param int $z
     * @return float|int|string
     */
    public function runCalculation($x = 0, $y = 0, $z = 0){
        if(is_numeric($x) && is_numeric($y) && is_numeric($x)){
            $this->x = $x;
            $this->y = $y;
            $this->z = $z;
            return $this->calculate($this->root);
        }
        else{
            return "not a number";
        }
    }


    /**
     * @param $arr
     * @return mixed
     */
    public function buildTree($arr)
    {
        foreach ($arr as $item){
            $this->insert($item);
        }
        $this->root = $this->stack->pop();
        return $this->root;
    }

    //x 42 + 2 ^ 7 y * + z -

    /**
     * @param $item
     */
    private function insert($item)
    {
        if(preg_match(Parser::NUMBER_PATTERN, $item)){
            $this->stack->push(new Node($item));
        }
        else if(preg_match(Parser::OPERATION_PATTERN, $item)){
            $leftNode = $this->stack->pop();
            $rightNode = $this->stack->pop();
            $this->stack->push(new Node($item, $leftNode, $rightNode));
        }
    }

    /**
     * @param Node $node
     * @return float|int|string
     */
    private function calculate(Node &$node)
    {
        if(preg_match(Parser::NUMBER_PATTERN, $node->value)){
            if(in_array($node->value, $this->vars)){
                return $this->assignValue($node->value); // возвращаем в замен x y z число
            }
            return $node->value;
        }
        else if(preg_match(Parser::OPERATION_PATTERN, $node->value)){
            switch ($node->value){
                case '+': {
                    return $this->calculate($node->right) + $this->calculate($node->left);
                }
                case '-': {
                    return $this->calculate($node->right) - $this->calculate($node->left);
                }
                case '*': {
                    return $this->calculate($node->right) * $this->calculate($node->left);
                }
                case '^': {
                    return pow($this->calculate($node->right), (int)$this->calculate($node->left));
                }
                case '/': {
                    try{
                        return $this->calculate($node->right) / $this->calculate($node->left);
                    }
                    catch (ArithmeticError $e){
                        exit('division by zero' . $e->getTraceAsString());
                    }
                }
            }
        }
    }

    /**
     * @param $value
     * @return mixed
     */
    private function assignValue($value)
    {
        switch ($value){
            case 'x': return $this->x;
            case 'y': return $this->y;
            case 'z': return $this->z;
            default: exit('not accepted variable');
        }
    }
}