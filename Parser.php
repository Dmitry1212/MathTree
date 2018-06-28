<?php
/**
 * Created by PhpStorm.
 * User: igroc
 * Date: 27.06.2018
 * Time: 9:13
 */

/**
 * Class Parser
 * Parse math expression to Reverse Polish notation
 *
 * @const array PRIORITY_OPERATION
 * @const string NUMBER_PATTERN
 * @const string OPEN_BRACKET
 * @const string CLOSE_BRACKET
 *
 * @property SplStack $stack
 * @property array $buffer
 *
 */
class Parser
{

    const PRIORITY_OPERATION = [
        "-" => ["priority" => "2", "association" => "left"],
        "+" => ["priority" => "2", "association" => "left"],
        "*" => ["priority" => "3", "association" => "left"],
        "/" => ["priority" => "3", "association" => "left"],
        "^" => ["priority" => "4", "association" => "right"]
    ];

    const NUMBER_PATTERN = "/[0-9a-zA-Z\.]/";
    const OPERATION_PATTERN = "/[\+\-\*\/\^]/";
    const OPEN_BRACKET = "(";
    const CLOSE_BRACKET = ")";

    private $stack;
    private $buffer;

    /**
     * Parser constructor.
     */
    public function __construct()
    {
        $this->stack = new SplStack();
        $this->buffer = [];
    }

    /**
     * @param $str
     * @return array
     */
    public function run($str)
    {
        $arr = $this->prepareString($str);
        return $this->parse($arr);
    }

    /**
     * @param $str
     * @return array|mixed|null|string|string[]
     */
    private function prepareString($str)
    {
        $str = preg_replace("/\s/", "", $str);
        $str = str_replace(",", ".", $str);
        $str = str_split($str);

        // проверяем на оператор в начале, если первый символ операнд ставим впереди ноль
        if (preg_match(self::OPERATION_PATTERN, $str[0])) {
            array_unshift($str, "0");
        }
        return $str;
    }

    /**
     * @param $value
     */
    private function pushOperation($value)
    {
        while (true) {
            if ($this->stack->isEmpty()) {
                $this->stack->push($value);
                break;
            }
            else {
                $lastOperation = $this->stack->pop();

                $prevPriority = self::PRIORITY_OPERATION[$lastOperation]['priority'];
                $currentPriority = self::PRIORITY_OPERATION[$value]['priority'];
                $currentAssociation = self::PRIORITY_OPERATION[$value]['association'];

                if ($currentAssociation === "left") {
                    if ($currentPriority > $prevPriority) {
                        $this->stack->push($lastOperation);
                        $this->stack->push($value);
                        break;
                    }
                    else {
                        $this->buffer[] = $lastOperation;
                    }
                }
                elseif ($currentAssociation === "right") {
                    if ($currentPriority >= $prevPriority) {
                        $this->stack->push($lastOperation);
                        $this->stack->push($value);
                        break;
                    }
                    elseif ($currentPriority < $prevPriority) {
                        $this->buffer[] = $lastOperation;
                    }
                }
            }
        }
    }


    /**
     * @param $arr
     * @return array
     */
    private function parse($arr)
    {
        $lastSymbolIsNumber = true;
        foreach ($arr as $key => $value) {
            if (preg_match(self::OPERATION_PATTERN, $value)) {
                $this->pushOperation($value);
                $lastSymbolIsNumber = false;
            }
            elseif (preg_match(self::NUMBER_PATTERN, $value)) {
                if ($lastSymbolIsNumber) {
                    $this->buffer[] = array_pop($this->buffer) . $value;
                }
                else {
                    $this->buffer[] = $value;
                    $lastSymbolIsNumber = true;
                }
            }
            elseif ($value == self::OPEN_BRACKET) {
                $this->stack->push($value);
                $lastSymbolIsNumber = false;
            }
            elseif ($value == self::CLOSE_BRACKET) {
                while (true) {
                    $symbol = $this->stack->pop();
                    if ($symbol != self::OPEN_BRACKET) {
                        $this->buffer[] = $symbol;
                    }
                    else{
                        break;
                    }
                }
                $lastSymbolIsNumber = false;
            }
        }

        $length = $this->stack->count();
        for ($i = 0; $i < $length; $i++) {
            $this->buffer[] = $this->stack->pop();
        }
        return $this->buffer;
    }
}