<?php

$string_example='/PID_Assignment/img/download.jpeg';
$de = "../img".strrchr($string_example, "/");

unlink($de);

// substr( $string , $start , $length )