<?php

if(isset($_POST['Signup'])){
    $mysqli = mysqli_connect("localhost", "root", "", "annapp");
    $author="author";
    $stmt = $mysqli->prepare("INSERT INTO user(username,password,role) VALUES(?,?,?)");
    $stmt->bind_param("sss",$_POST['username'], $_POST['password'],$author);
    $stmt->execute();
    $stmt = $mysqli->prepare("INSERT INTO authors(username,name,surname,orcidurl,url,specialty,userid) VALUES(?,?,?,?,?,?,(SELECT id FROM user WHERE username=?))");
    $stmt->bind_param("sssssss",$_POST['username'],$_POST['name'],$_POST['surname'],$_POST['orcidurl'],$_POST['url'],$_POST['specialty'],$_POST['username']);
    $stmt->execute();   
       echo "<script>alert('Sucess.Please login!');window.location.href='index.html';</script>";
       $stmt->close();
      
    }
    else if(isset($_POST['Signupuser']))
    {
        $user="user";
        $mysqli = mysqli_connect("localhost", "root", "", "annapp");
        $stmt = $mysqli->prepare("INSERT INTO user(username,password,role) VALUES(?,?,?)");
        $stmt->bind_param("sss",$_POST['username'],$_POST['password'],$user);
        $stmt->execute();
        echo "<script>alert('Sucess.Please login!');window.location.href='index.html';</script>";
       $stmt->close();
    }
    ?>



