<?php

use function PHPSTORM_META\type;

require "./config.php";
$currencies = array('ALL','AFN','ARS','AWG',
'AUD','AZN','BSD','BBD',
'BYN','BZD','BMD','BOB','BAM','BWP','BGN',
'BRL','BND','USD','EUR','LBP','JPY','AUD',
'KWD','SGD','CAD','GBP','MYR','UAE','HKD',
'SWD','BR','NZD','CYN','QR',
'KWON','NT','BHD','TB','SR','PHP','INR');
$date = date('o-n-j H:i:s');
if(isset($_REQUEST['from'])){
    $from = $_REQUEST['from'];
    $to = $_REQUEST['to'];
    $cl = curl_init("https://www.xe.com/api/protected/statistics/?from=$from&to=$to");
    curl_setopt($cl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($cl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($cl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cl,CURLOPT_HTTPHEADER,array(
        "authorization: Basic bG9kZXN0YXI6S2QxaWI1QloySzdFQzRNSWd5RUJRRWRCZmVaaHVyd2w="
    ));
    $data = curl_exec($cl);
    $data = json_decode($data,true);
    $low = $data['last30Days']['low'];
    $high = $data['last30Days']['high'];
    $date = $data['last30Days']['lowestTimeStamp'];
    echo $date;
    exit;
}


?>