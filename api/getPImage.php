<?php
require "./config.php";
session_start();
if(!(isset($_SESSION['logged'])) || $_SESSION['logged'] !== true){
    header('location: signup.html');
}
$email = $_SESSION['email'];
if(isset($_REQUEST['pname'])){
    $pname = $_REQUEST['pname'];
    $sql = 'SELECT * FROM categories WHERE pname="'.$pname.'"';
    $res = mysqli_query($conn,$sql);
    $r1 = mysqli_fetch_assoc($res);
    echo $r1['img'];
    exit;
}

?>