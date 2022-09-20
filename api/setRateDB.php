<?php
require "tr.php";
require "config.php";
$sql = 'INSERT INTO lirarates(bm,sayrafa) VALUES("'.$bm.'","'.$sayrafa.'")';
if($res = mysqli_query($conn,$sql)){
    echo 1;
}else{
    echo mysqli_error($conn);
}
exit;
?>