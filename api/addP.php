<?php
session_start();
if(!(isset($_SESSION['logged'])) || $_SESSION['logged'] !== true){
    header('location: signup.html');
}
$email = $_SESSION['email'];
if(isset($_REQUEST['pname'])){
    require "./config.php";
    $pname = $_GET['pname'];
    $pprice = $_GET['pprice'];
    $quantity = intval($_GET['quantity']);
    $sql = 'INSERT INTO products(pname,owner,quantity,price) VALUES("'.$pname.'","'.$email.'",'.$quantity.',"'.$pprice.'")';
        if(mysqli_query($conn,$sql)){
            $err = 0;
        }else{
            $err = mysqli_error($conn);
        }
    if($err === 0){
        $sql = 'select * FROM products';
        $res = mysqli_query($conn,$sql);
        $rows = mysqli_fetch_all($res);
        $nextid = $rows[mysqli_num_rows($res)-1][0];
        $value = $pprice*$quantity;
        $sql = "insert into ie_stats(type,sid,owner,value) VALUES('e',$nextid,'$email','$value')";
        if($res = mysqli_query($conn,$sql)){
            echo $nextid;
        }
    }else{
        echo $err;
    }
}
?>