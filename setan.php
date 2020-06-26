<?php
session_start();
require('conn.php');

if(isset($_SESSION['username'])){
  try{
if(isset($_POST['suba']))
{
    
  $stmt = $mysqli->prepare("INSERT INTO posts(username,title,note,url,author1,author2,author3,author4) VALUES (?,?,?,?,?,?,?,?)");
  $stmt->bind_param("ssssssss",$_SESSION['username'] ,$_POST['title'], $_POST['txtArea'],$_POST['url'],$_POST['author1'],$_POST['author2'],$_POST['author3'],$_POST['author4']);
        $stmt->execute();
        $stmt->close();
        $stmt = $mysqli->prepare("INSERT INTO article(articlename,volume,number,pages,document,postid) VALUES (?, ?,?,?,?,(SELECT id From posts WHERE title=?))");
        $stmt->bind_param("ssssss",$_POST['atitle'], $_POST['avolume'],$_POST['anumber'],$_POST['apages'],$_POST['adoi'],$_POST['title']);
        $stmt->execute();
        $stmt->close();
 
}
else if(isset($_POST['subinproc']))
{

  list($volume,$number)=volumenum();
  $stmt = $mysqli->prepare("INSERT INTO posts(username,title,note,url,author1,author2,author3,author4) VALUES (?,?,?,?,?,?,?,?)");
  $stmt->bind_param("ssssssss",$_SESSION['username'] ,$_POST['title'], $_POST['txtArea'],$_POST['url'],$_POST['author1'],$_POST['author2'],$_POST['author3'],$_POST['author4']);
    $stmt->execute();
    $stmt->close();
    $stmt = $mysqli->prepare("INSERT INTO inproceedings(booktitle,editor,series,volume,number,pages,organisation,publisher,adress,document,postid) VALUES (?, ?,?,?,?,?,?,?,?,?,(SELECT id From posts WHERE title=?))");
    $stmt->bind_param("sssssssssss",$_POST['intitle'], $_POST['ineditor'],$_POST['inseries'],$volume,$number,$_POST['inpages'],$_POST['inorg'],$_POST['inpub'],$_POST['inadress'],$_POST['indoi'],$_POST['title']);
    $stmt->execute();
    $stmt->close();
 
  
}
else if(isset($_POST['subb']))
{
  list($volume,$number)=volumenum();
 

  $stmt = $mysqli->prepare("INSERT INTO posts(username,title,note,url,author1,author2,author3,author4) VALUES (?,?,?,?,?,?,?,?)");
  $stmt->bind_param("ssssssss",$_SESSION['username'] ,$_POST['title'], $_POST['txtArea'],$_POST['url'],$_POST['author1'],$_POST['author2'],$_POST['author3'],$_POST['author4']);
    $stmt->execute();
    $stmt->close();
    $stmt = $mysqli->prepare("INSERT INTO books(publisher,volume,number,series,adress,edition,isbn,issn,postid) VALUES (?,?,?,?,?,?,?,?,(SELECT id From posts WHERE title=?))");
    $stmt->bind_param("sssssssss",$_POST['bpub'], $volume,$number,$_POST['bseries'],$_POST['badress'],$_POST['bedition'],$_POST['bisbn'],$_POST['bissn'],$_POST['title']);
    $stmt->execute();
    $stmt->close();
  
}
else if (isset($_POST['subinbook']))
{
  echo "yo";
  list($volume,$number)=volumenum();
  
    $stmt = $mysqli->prepare("INSERT INTO posts(username,title,note,url,author1,author2,author3,author4) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssss",$_SESSION['username'] ,$_POST['title'], $_POST['txtArea'],$_POST['url'],$_POST['author1'],$_POST['author2'],$_POST['author3'],$_POST['author4']);
    $stmt->execute();
    $stmt->close();
    $stmt = $mysqli->prepare("INSERT INTO inbooks(publisher,adress,chapter,pages,volume,number,series,type,edition,isbn,issn,postid) VALUES (?,?,?,?,?,?,?,?,?,?,?,(SELECT id From posts WHERE title=?))");
    $stmt->bind_param("ssssssssssss",$_POST['inbpub'], $_POST['inbadress'],$_POST['inbchap'],$_POST['inbpages'],$volume,$number,$_POST['inbseries'],$_POST['inbtype'],$_POST['inbedition'],$_POST['inbisbn'],$_POST['inbissn'],$_POST['title']);
    $stmt->execute();
    $stmt->close();
  
}
} catch(Exception $e) {
  if($mysqli->errno === 1062) echo '<script type="text/JavaScript">alert("Article already exists.");</script>' ; 
}
  echo '<script type="text/JavaScript">alert("Post set.");window.location.href = "profile.html";</script>' ; 
}
else
echo '<script type="text/JavaScript">alert("You have to sign up  in order to post an annaouncement.");window.location.href = "profile.html";</script>' ; 

function volumenum(){
  if(isset($_POST['involumeb']))
  {
    $volume=$_POST['innumvol'];
    $number="";
    return array($volume,$number);
  }
  else if(isset($_POST['innumberb']))
  {
   $volume="";
   $number=$_POST['innumvol'];
   return array($volume,$number);
  }
}



?>