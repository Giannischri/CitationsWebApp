<?php
session_start();
require('conn.php');
if(isset($_POST['profsub']))
{
    $username= $_POST['usrname'];
    
    
    $name= $_POST['name'];
    $surname= $_POST['surname'];
    $orcid=$_POST['orcidurl'];
    $url=$_POST['url'];
    $specialty=$_POST['specialty'];
    if(isset($_SESSION['username']))
    {
        $stmt = $mysqli->prepare("UPDATE user SET username=? WHERE username=?");
    $stmt->bind_param("ss",$username,$_SESSION['usrname']);
    $stmt->execute();
    if($_SESSION['role']=="author")
    {
    $stmt2 = $mysqli->prepare("UPDATE authors SET username=?,name=?,surname=?,orcidurl=?,url=?,specialty=? WHERE username=?");
    $stmt2->bind_param("sssssss",$username, $name,$surname, $orcid, $url,$specialty,$_SESSION['username']);
    $stmt2->execute();
    }
    }
    
    echo '<script type="text/JavaScript">  
    alert("Your credentials were updated successfully"); 
    window.location.href = "index.html";
     </script>' ;
      
          echo "</script>";
          $stmt->close();
          $stmt2->close();
}
else if(isset($_POST['cpassword']))
{
    $stmt=$mysqli->prepare("SELECT id FROM user WHERE username=? AND password=?");
    $stmt->bind_param("ss",$_SESSION['username'],$_POST['opassword']);
    $stmt->execute();
    $stmt->fetch();
    $numberofrows = $stmt->num_rows;
    if($numberofrows!=1)
    {
        echo '<script type="text/JavaScript">  
        alert("Your password was incorrect"); 
        window.location.href = "profile.html";
         </script>' ;
    }
    else
    {
        $stmt=$mysqli->prepare("UPDATE user SET password=? WHERE username=?");
      $stmt->bind_param("s",$_POST['npassword'],$_SESSION['username']);
    $stmt->execute();
    echo '<script type="text/JavaScript">  
        alert("Your password was update successfully"); 
        window.location.href = "profile.html";
         </script>' ;
    }
    $stmt->close();
}
?>