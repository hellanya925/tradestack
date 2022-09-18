<?php
session_start();
if(!(isset($_SESSION['logged'])) || $_SESSION['logged'] !== true){
    header('location: signup.html');
}
$email = $_SESSION['email'];
if(isset($_REQUEST['id'])){
    require "./config.php";
    $id = $_GET['id'];
    $quantity = floatval($_GET['quantity']);
    $price = floatval($_GET['price']);
    $sql = 'select * from products where id='.$id;
    $res = mysqli_query($conn,$sql) or die(mysqli_errno($conn));
    $row = mysqli_fetch_assoc($res);
    if($row['quantity'] > $quantity){
        $newq = $row['quantity'] - $quantity;
        $sql = 'UPDATE products SET quantity='.$newq.' WHERE id='.$id;
    }else if($row['quantity'] == $quantity){
        $sql = 'DELETE FROM products WHERE id='.$id;
    }
    
    if(mysqli_query($conn,$sql)){
        $sql = 'INSERT INTO sold(pname,owner,quantity,price) VALUES("'.$row['pname'].'","'.$email.'",'.$quantity.',"'.$price.'")';
        if(mysqli_query($conn,$sql)){
            $value = $price*$quantity;
            $sql = "insert into ie_stats(type,sid,owner,value) VALUES('i',$id,'$email','$value')";
            if($res = mysqli_query($conn,$sql)){
                echo 1;
            }

        }else{
            echo mysqli_error($conn);
        }
    }else{
        echo mysqli_error($conn);
    }
}
?>