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
if(isset($_REQUEST['from']) && isset($_REQUEST['to'])){
    $from = $_REQUEST['from'];
    $to = $_REQUEST['to'];
    $dayNumber = intval(date('j'));
    $start = date('Y-m-'.($dayNumber-1));
    $today = date('Y-m-j');
    $cl = curl_init("https://cc-api.oanda.com/cc-api/v1/currencies?base=".$from."&quote=".$to."&data_type=chart&start_date=".$start."&end_date=".$today);
    curl_setopt($cl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($cl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($cl, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($cl);
    curl_close($cl);
    $data = json_decode($data,true);
    $low = $data['response'][0]['low_bid'];
    $high = $data['response'][0]['high_bid'];
    echo '{
        "low":'.$low.',
        "high":'.$high.'
    }';
    exit;
}


?>