<?php
require "functions.php";
$data = file_get_contents('https://lbprate.com');
$index = strhasstr($data,'id="marketRate"');
$temp = strPartition($data,strhasstr($data,'id="marketRate"')+357,strhasstr($data,'id="marketRate"')+364);
$temp = removeChar($temp,',');
$res = '{"bm":';
$res .= $temp.',';
$temp = strPartition($data,strhasstr($data,'id="sayrafaRate"')+346,strhasstr($data,'id="sayrafaRate"')+354);
$temp = removeChar($temp,',');
$res .= '"sayrafa":'.$temp.'}';
echo $res;
exit;