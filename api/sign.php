<?php
require "./config.php";
if(isset($_REQUEST['first'])){
    $first = $_GET['first'];
    $last = $_GET['last'];
    $email = $_GET['email'];
    $passwd = $_GET['passwd'];
    $passwd = password_hash($passwd,PASSWORD_BCRYPT);
    $sql = 'INSERT INTO users(first,last,email,passwd) VALUES("'.$first.'","'.$last.'","'.$email.'","'.$passwd.'")';
    if(mysqli_query($conn,$sql)){
        echo 1;
    }else{
        echo -1;
    }
}
?>