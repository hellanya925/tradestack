<?php
require "./config.php";
session_start();
if(!(isset($_SESSION['logged'])) || $_SESSION['logged'] !== true){
    header('location: signup.html');
}
$email = $_SESSION['email'];
if(isset($_REQUEST['id'])){
    $id = $_GET['id'];
    $quantity = $_GET['quantity'];
    $sql = "SELECT * FROM products WHERE id=$id";
    $res = mysqli_query($conn,$sql);
    $row = mysqli_fetch_assoc($res);
    if(intval($row['quantity']) < intval($quantity)){
        echo -1;
        exit;
    }else if($row['quantity'] === $quantity){
        $sql = "DELETE from products WHERE id='$id'";
        $res = mysqli_query($conn,$sql);
        if($res){
            echo 2;
        }
    }else{
        $new = intval($row['quantity']) - intval($quantity);
        $sql = "UPDATE products SET quantity='$new' WHERE id='$id'";
        $res = mysqli_query($conn,$sql);
        if($res){
            echo 1;
        }
    }
    $sql = "DELETE FROM ie_stats WHERE sid=$id";
    $res = mysqli_query($conn,$sql);
    exit;
}

?>