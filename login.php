<?php 
session_start();
require('conn.php');
if(isset($_SESSION['username']))
{
   echo "<script>alert('You are already logged in');</script>";
}
if(isset($_POST['logsub'])){
    
    $pword=$_POST['psw'];
    $email=$_POST['email'];
    $stmt = $mysqli->prepare("SELECT username FROM user WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $email,$pword);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows === 1){
   echo "<script>alert('Sucess.');window.location.href='index.html';</script>";
   $_SESSION['username'] = $email;
}
   else
    echo "<script>alert('Error!Wrong credentials');window.location.href='index.html';</script>";
}
   ?>