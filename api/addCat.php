<?php
session_start();
if(!(isset($_SESSION['logged'])) || $_SESSION['logged'] !== true){
    header('location: signup.html');
}
$email = $_SESSION['email'];
if(isset($_REQUEST['pname'])){
    require "./config.php";
    $pname = $_POST['pname'];
    if(isset($_FILES['file']['name'])){
            $filename = $_FILES['file']['name'];
            $location = '../products/'.$filename;
            $file_extension = pathinfo($location, PATHINFO_EXTENSION);
            $file_extension = strtolower($file_extension);
            $valid_ext = array('png','jpeg','jpg','jfif','svg');
            $response = 0;
           if(in_array($file_extension,$valid_ext)){
              // Upload file
              if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
                 $response = 1;
            } 
        }
    }else{
        $filename = 'default.png';
    }
    
    $sql = "SELECT * FROM categories WHERE owner='$email' AND pname='$pname'";
    $res = mysqli_query($conn,$sql);
    if(mysqli_num_rows($res) > 0){
        echo -1;
        exit;
    }
    $sql = 'INSERT INTO categories(pname,owner,img) VALUES("'.$pname.'","'.$email.'","products/'.$filename.'")';
    if(mysqli_query($conn,$sql)){
        echo 1;
    }else{
        echo mysqli_error($conn);
    }
}
?>