<?php
/**
 * Created by PhpStorm.
 * User: igroc
 * Date: 27.06.2018
 * Time: 10:11
 */

require 'Parser.php';
require 'Node.php';
require 'Tree.php';

$parser = new Parser();
$tree = new Tree();

$str = "(x+2)^2+7*y-z";
$arr = $parser->run($str);
$tree->buildTree($arr);
echo "<pre>";
print_r($tree);
echo "</pre>";

echo $str;
echo '<br>равно = ' . $tree->runCalculation(2, 1, 22); //1
echo '<br>равно = ' . $tree->runCalculation(2, 1, 20); //3


$parser2 = new Parser();
$tree2 = new Tree();

$str2 = "-x*2+4/(4-2)^2+3";
$arr2 = $parser2->run($str2);

$tree2->buildTree($arr2);
echo "<pre>";
print_r($tree2);
echo "</pre>";

echo $str2;
echo '<br>равно = ' . $tree2->runCalculation(2); //0
echo '<br>равно = ' . $tree2->runCalculation(3); //-2











