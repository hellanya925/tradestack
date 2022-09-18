<?php
require './config.php';
session_start();
if(!(isset($_SESSION['logged'])) || $_SESSION['logged'] !== true){
    header('location: signup.html');
}
$email = $_SESSION['email'];
if(isset($_REQUEST['y1'])){
    $year1 = intval($_REQUEST['y1']);
    $month1 = intval($_REQUEST['m1']);
    $day1 = intval($_REQUEST['d1']);
    $year2 = intval($_REQUEST['y2']);
    $month2 = intval($_REQUEST['m2']);
    $day2 = intval($_REQUEST['d2']);
}else{
    exit;
}
$sql = "select * FROM sold WHERE owner='$email'";
$res = mysqli_query($conn,$sql);
$earnings = $sales = $add = 0;
while($row = mysqli_fetch_assoc($res)){
    $sdate = strtotime($row['date_sold']);
    $sday = intval(date('d',$sdate));
    $sMonth = intval(date('m',$sdate));
    $sYear = intval(date('o',$sdate));
    $date1 = strtotime($year1.'-'.$month1.'-'.$day1.' 00:00:00');
    $date2 = strtotime($year2.'-'.$month2.'-'.$day2.' 23:59:59');
    
    
    if($sdate >= $date1 && $sdate <= $date2){
        $add = 1;
    }else{
        $add = 0;
    }
    if($add == 1){
        $earnings += floatval($row['price'])*intval($row['quantity']);
        $sales += intval($row['quantity']);
    }else{
        continue;
    }
}
echo '{"earnings":'.$earnings.',"sales":'.$sales.'}';
exit;
?>