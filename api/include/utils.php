<?php
function checkStringDateFormat(string $str, string $format) : bool
{
    $date = DateTime::createFromFormat($format, $str);

    return ($date && $date->format($format) == $str);
}

function checkFloatDecimalPlaces(float $num) : int
{
    $str = strval($num);
    return strlen(substr(strrchr($str, "."), 1));
}
?>
