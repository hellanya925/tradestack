<?php
require "./config.php";
if(isset($_REQUEST['uname'])){
    $uname = $_GET['uname'];
    $passwd = $_GET['passwd'];
    $sql = "SELECT * FROM users WHERE email='$uname'";
    $res = mysqli_query($conn,$sql);
    if(mysqli_num_rows($res) == 0){
        echo 0;
    }else{
        $row = mysqli_fetch_assoc($res);
        if(password_verify($passwd,$row['passwd'])){
            session_start();
            $_SESSION['logged'] = true;
            $_SESSION['first'] = $row['first'];
            $_SESSION['last'] = $row['last'];
            $_SESSION['email'] = $row['email'];
            echo 1;
        }else{
            echo -1;
        }
    }
    exit;
}


?>