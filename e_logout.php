<?php
session_start();
include("database.php");
            $id=$_SESSION["e_id"];
            $password=$_SESSION["e_password"];
            $email=$_SESSION["e_email"];
            $type=$_SESSION["e_type"];

    $sql="INSERT INTO logout (id,password,type,email) 
          VALUES ('$id', '$password','$type','$email') ";
    mysqli_query($conn,$sql);
    echo "you are logged out";
    session_destroy();
   header("Location: login.php");
?>