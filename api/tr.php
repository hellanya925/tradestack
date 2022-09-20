<?php
require "functions.php";
$data = file_get_contents('https://lbprate.com');
$index = strhasstr($data,'id="marketRate"');
$temp = strPartition($data,strhasstr($data,'id="marketRate"')+357,strhasstr($data,'id="marketRate"')+364);
$bm = removeChar($temp,',');
$res = '{"bm":';
$res .= $bm.',';
$temp = strPartition($data,strhasstr($data,'id="sayrafaRate"')+346,strhasstr($data,'id="sayrafaRate"')+354);
$sayrafa = removeChar($temp,',');
$res .= '"sayrafa":'.$sayrafa.'}';
echo $res;