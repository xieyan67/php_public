<?php
/**
 * Created by PhpStorm.
 * User: xieyan
 * Date: 2019/2/14
 * Time: 上午11:03
 */
$a = [1,18,14,5,78,9,9,3,3];
function mysort(&$arr, $h, $t)
{
    if ($h < $t) {
        $i = $h;
        $j = $t;
        $x = $arr[$h];
        while ($i < $j)
        {
            while($i<$j && $arr[$j] >= $x)$j--;
            if($i<$j)$arr[$i++] = $arr[$j];
            while($i<$j && $arr[$i] < $x) $i++;
            if($i<$j)$arr[$j--] = $arr[$i];
        }
        $arr[$i] = $x;
        mysort($arr, $h, $i-1);
        mysort($arr, $i+1, $t);
    }
}
print_r($a);
mysort($a, 0, 8);
print_r($a);

