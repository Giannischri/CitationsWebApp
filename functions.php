<?php
use RenanBr\BibTexParser\Listener;
use RenanBr\BibTexParser\Parser;
use RenanBr\BibTexParser\Processor;
require 'vendor/autoload.php';
  session_start();
  
  class article{
    public $id="";
    public $username="";
    public $title="";
    public $note="";
    public $date="";
    public $url="";
    public $articlename="";
    public $number="";
    public $volume="";
    public $pages="";
    public $document="";
    public $authors=array();
  }
  class inproceeding{
    public $id="";
    public $username="";
    public $title="";
    public $note="";
    public $date="";
    public $url="";
    public $booktitle="";
    public $editor="";
    public $series="";
    public $volume="";
    public $number="";
    public $pages="";
    public $organisation="";
    public $publisher="";
    public $adress="";
    public $document="";
    public $authors=array();
  }
  class book{
    public $id="";
    public $username="";
    public $title="";
    public $note="";
    public $date="";
    public $url="";
    public $series="";
    public $volume="";
    public $number="";
    
    public $publisher="";
    public $adress="";
    public $edition="";
    public $isbn="";
    public $issn="";
    public $authors=array();

  }
  class inbook{
    public $id="";
    public $username="";
    public $title="";
    public $note="";
    public $date="";
    public $url="";
    public $series="";
    public $volume="";
    public $number="";
    public $publisher="";
    public $adress="";
    public $edition="";
    public $chapter="";
    public $pages="";
    public $type="";
    public $isbn="";
    public $issn="";
    public $authors=array();
  }
 
  function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
  function usercheck()
  {
       if(isset($_SESSION['username']))
       {
        $mysqli = mysqli_connect("localhost", "root", "", "annapp");
        $stmt = $mysqli->prepare("SELECT role FROM user WHERE username = ?");
        $stmt->bind_param("s", $_SESSION['username']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $_SESSION['role']=$row['role'];
       
       }
       $object = (object) ['name' => $_SESSION['username'],
        'role' =>$_SESSION['role']
      ];
       return json_encode($object);
  }
  function logout()
  {
       session_destroy();
       
       
  }

  function getprofstuff()
  {
      
          $mysqli = mysqli_connect("localhost", "root", "", "annapp");
          $stmt = $mysqli->prepare("SELECT * FROM authors WHERE username = ?");
          $stmt->bind_param("s", $_SESSION['username']);
          $stmt->execute();
          $result = $stmt->get_result();
          while($row = $result->fetch_assoc()) {
            
            $return['username']=$row['username'];
            $return['name']=$row['name'];
            $return['surname']=$row['surname'];
            $return['orcid']=$row['orcidurl'];
            $return['url']=$row['url'];
            $return['specialty']=$row['specialty'];        
          }
         
          $stmt->close();
          return json_encode($return);
          
  }
  function getuserposts()
  {
      
       $return=array();
     $mysqli = mysqli_connect("localhost", "root", "", "annapp");
     $stmt = $mysqli->prepare("SELECT * FROM posts,article WHERE postid=posts.id AND posts.username=?");
     $stmt->bind_param("s", $_SESSION['username']);
     $stmt->execute();
     $result = $stmt->get_result();
     
     while($row = $result->fetch_assoc()) {
         $a=new article();
         $a->id=$row['postid'];
         $a->username=$row['username'];
         $a->title=$row['title'];
         $a->note=$row['note'];
         $a->date=$row['date'];
         $a->url=$row['url'];
         $a->articlename=$row['articlename'];
         $a->number=$row['number'];
         $a->pages=$row['pages'];
         $a->volume=$row['volume'];
         $a->document=$row['document'];
         $a->authors[]=$row['author1'];
         $a->authors[]=$row['author2'];
         $a->authors[]=$row['author3'];
         $a->authors[]=$row['author4'];
         $return[]=$a;
         
             
     }
     $stmt->close();
    
     $stmt = $mysqli->prepare("SELECT * FROM posts,inproceedings WHERE postid=posts.id AND posts.username=?");
     $stmt->bind_param("s", $_SESSION['username']);
     $stmt->execute();
     $result = $stmt->get_result();
    
     while($row = $result->fetch_assoc()) {
         $inproc=new inproceeding();
         $inproc->id=$row['postid'];
         $inproc->username=$row['username'];
         $inproc->title=$row['title'];
         $inproc->note=$row['note'];
         $inproc->date=$row['date'];
         $inproc->url=$row['url'];
         $inproc->booktitle=$row['booktitle'];
         $inproc->editor=$row['editor'];
         $inproc->series=$row['series'];
         $inproc->volume=$row['volume'];
         $inproc->number=$row['number'];
         $inproc->pages=$row['pages'];
         $inproc->organisation=$row['organisation'];
         $inproc->publisher=$row['publisher'];
         $inproc->adress=$row['adress'];
         $inproc->document=$row['document']; 
         $inproc->authors[]=$row['author1'];
         $inproc->authors[]=$row['author2'];
         $inproc->authors[]=$row['author3'];
         $inproc->authors[]=$row['author4'];    
         $return[]=$inproc;  
             
     }
     $stmt->close();
     $stmt = $mysqli->prepare("SELECT * FROM posts,books WHERE postid=posts.id AND posts.username=?");
     $stmt->bind_param("s",$_SESSION['username']);
     $stmt->execute();
     $result = $stmt->get_result();
    
     while($row = $result->fetch_assoc()) {
         $book=new book();
         $book->id=$row['postid'];
         $book->username=$row['username'];
         $book->title=$row['title'];
         $book->note=$row['note'];
         $book->date=$row['date'];
         $book->url=$row['url'];
         $book->publisher=$row['publisher'];
         
         $book->series=$row['series'];
         $book->volume=$row['volume'];
         $book->number=$row['number'];
         $book->edition=$row['edition'];
         $book->adress=$row['adress'];
         $book->isbn=$row['isbn'];
         $book->issn=$row['issn'];
         $book->authors[]=$row['author1'];
         $book->authors[]=$row['author2'];
         $book->authors[]=$row['author3'];
         $book->authors[]=$row['author4'];    
         $return[]=$book;  
             
     }
     $stmt->close();
     $stmt = $mysqli->prepare("SELECT * FROM posts,inbooks WHERE postid=posts.id AND posts.username=?");
     $stmt->bind_param("s", $_SESSION['username']);
     $stmt->execute();
     $result = $stmt->get_result();
    
     while($row = $result->fetch_assoc()) {
       
         $inbook=new inbook();
         $inbook->id=$row['postid'];
         $inbook->username=$row['username'];
         $inbook->title=$row['title'];
         $inbook->note=$row['note'];
         $inbook->date=$row['date'];
         $inbook->url=$row['url'];
       
         $inbook->publisher=$row['publisher'];
         $inbook->pages=$row['pages'];
         $inbook->chapter=$row['chapter'];    
         $inbook->series=$row['series'];
         $inbook->volume=$row['volume'];
         $inbook->number=$row['number'];
         $inbook->edition=$row['edition'];
         $inbook->type=$row['type'];
         $inbook->adress=$row['adress'];
         $inbook->isbn=$row['isbn'];
         $inbook->issn=$row['issn'];
         $inbook->authors[]=$row['author1'];
         $inbook->authors[]=$row['author2'];
         $inbook->authors[]=$row['author3'];
         $inbook->authors[]=$row['author4'];    
         $return[]=$inbook;  
             
     }
     
     $stmt->close();    
     return json_encode($return);
     
  }
  function updatepost()
  {
              $data=$_POST['array'];
            try{
              $mysqli = mysqli_connect("localhost", "root", "", "annapp");
            $stmt = $mysqli->prepare("UPDATE posts SET title=?,note=?,url=?,author1=?,author2=?,author3=?,author4=? WHERE id=? && username=?");
            $stmt->bind_param("sssssssss",$data[0]['value'],$data[1]['value'],$data[2]['value'],$data[3]['value'],$data[4]['value'],$data[5]['value'],$data[6]['value'],$_POST['id'],$_SESSION['username']);
            $stmt->execute();

            if($data[0]['name']=='atitle'){
            $stmt = $mysqli->prepare("UPDATE article SET articlename=?,volume=?,number=?,pages=?,document=? WHERE postid=?");
            $stmt->bind_param("ssssss",$data[7]['value'],$data[8]['value'],$data[10]['value'],$data[9]['value'],$data[11]['value'],$_POST['id']);
          $stmt->execute();
            }
            else if($data[0]['name']=='booktitle'){
          $stmt = $mysqli->prepare("UPDATE books SET publisher=?,volume=?,number=?,series=?,adress=?,edition=?,isbn=?,issn=? WHERE postid=?");
            $stmt->bind_param("sssssssss",$data[7]['value'],$data[9]['value'],$data[12]['value'],$data[11]['value'],$data[8]['value'],$data[13]['value'],$data[10]['value'],$data[14]['value'],$_POST['id']);
          $stmt->execute();
            }
          else if($data[0]['name']=='inbooktitle'){
          $stmt = $mysqli->prepare("UPDATE inbooks SET publisher=?,volume=?,number=?,series=?,adress=?,pages=?,type=?,edition=?,chapter=?,isbn=?,issn=? WHERE postid=?");
            $stmt->bind_param("ssssssssssss",$data[7]['value'],$data[13]['value'],$data[11]['value'],$data[12]['value'],$data[8]['value'],$data[10]['value'],$data[15]['value'],$data[14]['value'],$data[9]['value'],$data[16]['value'],$data[17]['value'],$_POST['id']);
          $stmt->execute();
          }
          else if($data[0]['name']=='inproctitle'){
          $stmt = $mysqli->prepare("UPDATE inproceedings SET booktitle=?,volume=?,number=?,series=?,editor=?,pages=?,organisation=?,publisher=?,adress=?,document=? WHERE postid=?");
            $stmt->bind_param("sssssssssss",$data[13]['value'],$data[10]['value'],$data[11]['value'],$data[15]['value'],$data[12]['value'],$data[14]['value'],$data[7]['value'],$data[8]['value'],$data[9]['value'],$data[16]['value'],$_POST['id']);
          $stmt->execute();
          }
           }
            catch( mysqli_sql_exception $e )
            {
                return $e->getMessage();
                die;
            }
  
      
  }
  function deletepost()
  {
    $data=$_POST['array'];
    $mysqli = mysqli_connect("localhost", "root", "", "annapp");
    if($data[0]['name']=='atitle'){     
      $stmt = $mysqli->prepare("DELETE FROM article WHERE postid=?");
      $stmt->bind_param("s",$_POST['id']);
      $stmt->execute();
    }
    else if($data[0]['name']=='booktitle'){
      $stmt = $mysqli->prepare("DELETE FROM books WHERE postid=?");
      $stmt->bind_param("s",$_POST['id']);
      $stmt->execute();
    }
    else if($data[0]['name']=='inbooktitle'){
      $stmt = $mysqli->prepare("DELETE FROM inbooks WHERE postid=?");
      $stmt->bind_param("s",$_POST['id']);
      $stmt->execute();
    }
    else if($data[0]['name']=='inproctitle'){
      $stmt = $mysqli->prepare("DELETE FROM inproceedings WHERE postid=?");
      $stmt->bind_param("s",$_POST['id']);
      $stmt->execute();
    }
    
          
  }
  function exportpost()
  {
    $checkedids=$_POST['array'];
    $userposts=getuserposts();
   $posts=json_decode($userposts,true);
   $astring="";
   $inprocstring="";
   $bookstring="";
   $inbookstring="";
    for($i=0;$i<count($posts);$i++)
    {
      for($j=0;$j<count($checkedids);$j++)
      {
        if($checkedids[$j]==$posts[$i]['id'])
        {
          if(count($posts[$i])==12)
          {
            $author="";
            for($z=0;$z<count($posts[$i]['authors']);$z++)
            {
              if($posts[$i]['authors'][$z]!="")
              $author.=$posts[$i]['authors'][$z].',';
            }
            $author=substr($author,0,-1);
           $astring.='
           @article{
              author={'.$author.'},
              title={'.$posts[$i]['title'].'},
              note={'.$posts[$i]['note'].'},
              date={'.$posts[$i]['date'].'},
              url={'.$posts[$i]['url'].'},
              articlename={'.$posts[$i]['articlename'].'},
                number={'.$posts[$i]['number'].'},
                pages={'.$posts[$i]['pages'].'},
               volume={'.$posts[$i]['volume'].'},
                document={'.$posts[$i]['document'].'}
              
            }';
           
          }
          else if(count($posts[$i])==17)
          {
            $author="";
            for($z=0;$z<count($posts[$i]['authors']);$z++)
            {
              if($posts[$i]['authors'][$z]!="")
              $author.=$posts[$i]['authors'][$z].',';
              
            }
            $author=substr($author,0,-1);
              $inprocstring.=' 
              @inproceeding{
                author={'.$author.'},
                title={'.$posts[$i]['title'].'},
                note={'.$posts[$i]['note'].'},
                date={'.$posts[$i]['date'].'},
                url={'.$posts[$i]['url'].'},
                booktitle={'.$posts[$i]['booktitle'].'},
                  editor={'.$posts[$i]['editor'].'},
                  series={'.$posts[$i]['series'].'},
                 volume={'.$posts[$i]['volume'].'},
                  number={'.$posts[$i]['number'].'},
                  pages={'.$posts[$i]['pages'].'},
                  organisation={'.$posts[$i]['organisation'].'},
                  publisher={'.$posts[$i]['publisher'].'},
                  adress={'.$posts[$i]['adress'].'},
               document={'.$posts[$i]['document'].'}   
                
              }';
          }
          else if(count($posts[$i])==15)
          {
            $author="";
            for($z=0;$z<count($posts[$i]['authors']);$z++)
            {
              if($posts[$i]['authors'][$z]!="")
              $author.=$posts[$i]['authors'][$z].',';
             
            }
            $author=substr($author,0,-1);
               $bookstring.='
               @book{
                author={'.$author.'},
                title={'.$posts[$i]['title'].'},
                note={'.$posts[$i]['note'].'},
                date={'.$posts[$i]['date'].'},
                url={'.$posts[$i][' url'].'},
                publisher={'.$posts[$i]['publisher'].'},        
                  series={'.$posts[$i]['series'].'},
                  volume={'.$posts[$i]['volume'].'},
                  number={'.$posts[$i]['number'].'},
                  edition={'.$posts[$i]['edition'].'},
                  adress={'.$posts[$i]['adress'].'},
                  isbn={'.$posts[$i]['isbn'].'},
                  issn={'.$posts[$i]['issn'].'}
                
              }';
          }
          else if(count($posts[$i])==18)
          {
            $author="";
            for($z=0;$z<count($posts[$i]['authors']);$z++)
            {
              if($posts[$i]['authors'][$z]!=""){
              $author.=$posts[$i]['authors'][$z].',';
              }
            }
            $author=substr($author,0,-1);
               $inbookstring.='
               @inbook{
                author={'.$author.'},
                title={'.$posts[$i]['title'].'},
                note={'.$posts[$i]['note'].'},
                date={'.$posts[$i]['date'].'},
                url={'.$posts[$i]['url'].'},
                publisher={'.$posts[$i][' publisher'].'},
                pages={'.$posts[$i]['pages'].'},
                chapter={'.$posts[$i]['chapter'].'},    
                series={'.$posts[$i]['series'].'},
                volume={'.$posts[$i]['volume'].'},
                number={'.$posts[$i]['number'].'},
                edition={'.$posts[$i]['edition'].'},
                type={'.$posts[$i]['type'].'},
                adress={'.$posts[$i]['adress'].'},
                isbn={'.$posts[$i]['isbn'].'},
                issn={'.$posts[$i]['issn'].'}
              }';
          }
          
        }
      }
    }
   $bibitem='Post maker:'.$_SESSION['username'].' '.$astring . ' ' . $inprocstring . ' ' . $bookstring . ' ' .$inbookstring;
    return $bibitem;
  }
  function getstats()
  {
    $mysqli = mysqli_connect("localhost", "root", "", "annapp");
    if($_SESSION['role']=="accountable" || $_SESSION['role']=="admin")
    {
    $array=Array();
    $stmt = $mysqli->prepare("SELECT(
      SELECT COUNT(*)
      FROM  posts
      ) AS total,
      (
      SELECT COUNT(*)
      FROM  article
      ) AS atotal,
      (
      SELECT COUNT(*)
      FROM   inproceedings
      ) AS inproctotal,
      (
      SELECT COUNT(*)
      FROM   books
      ) AS booktotal, 
      (
      SELECT COUNT(*)
      FROM   inbooks
      ) AS inbooktotal");
    $stmt->execute();
    $row = $stmt->get_result()->fetch_row();
    $stmt = $mysqli->prepare("SELECT min(date),max(date) FROM posts");
    $stmt->execute();
    $postdates= $stmt->get_result()->fetch_row();
    $stmt = $mysqli->prepare("SELECT min(date),max(date) FROM posts,article WHERE posts.id=postid");
    $stmt->execute();
    $articledates= $stmt->get_result()->fetch_row();
    $stmt = $mysqli->prepare("SELECT min(date),max(date) FROM posts,inproceedings WHERE posts.id=postid");
    $stmt->execute();
    $inprocdates= $stmt->get_result()->fetch_row();
    $stmt = $mysqli->prepare("SELECT min(date),max(date) FROM posts,books WHERE posts.id=postid");
    $stmt->execute();
    $bookdates= $stmt->get_result()->fetch_row();
    $stmt = $mysqli->prepare("SELECT min(date),max(date) FROM posts,inbooks WHERE posts.id=postid");
    $stmt->execute();
    $inbookdates= $stmt->get_result()->fetch_row();
    $pubs=array();
    class publisher
    {
      public $name="";
      public $postsnum="";
    }
    $stmt = $mysqli->prepare("SELECT DISTINCT publisher FROM inbooks");
    $stmt->execute();
    $inbookpubs=$stmt->get_result()->fetch_row();
    for($i=0;$i<count($inbookpubs);$i++){
      $p=new publisher();
      $p->name=$inbookpubs[$i];
    $stmt = $mysqli->prepare("SELECT count(*) FROM inbooks WHERE publisher=?");
    $stmt->bind_param("s", $inbookpubs[$i]);
    $stmt->execute();
    $p->postsnum=$stmt->get_result()->fetch_row();
    $pubs[]=$p;
    }
    $stmt = $mysqli->prepare("SELECT DISTINCT publisher FROM inproceedings");
    $stmt->execute();
    $inprocpubs=$stmt->get_result()->fetch_row();
    for($i=0;$i<count($inprocpubs);$i++){
      $p=new publisher();
      $p->name=$inprocpubs[$i];
    $stmt = $mysqli->prepare("SELECT count(*) FROM inproceedings WHERE publisher=?");
    $stmt->bind_param("s", $inprocpubs[$i]);
    $stmt->execute();
    $p->postsnum=$stmt->get_result()->fetch_row();
    $pubs[]=$p;
    }
    $stmt = $mysqli->prepare("SELECT DISTINCT publisher FROM books");
    $stmt->execute();
    $bookpubs=$stmt->get_result()->fetch_row();
    for($i=0;$i<count($bookpubs);$i++){
      $p=new publisher();
      $p->name=$bookpubs[$i];
    $stmt = $mysqli->prepare("SELECT count(*) FROM books WHERE publisher=?");
    $stmt->bind_param("s", $bookpubs[$i]);
    $stmt->execute();
    $p->postsnum=$stmt->get_result()->fetch_row();
    $pubs[]=$p;
    }
    $stmt = $mysqli->prepare("SELECT YEAR(date),count(*) FROM posts GROUP BY YEAR(date)
    ORDER BY 1");
    $stmt->execute();
    $columchart=$stmt->get_result()->fetch_array();
    $stmt = $mysqli->prepare("SELECT adress,count(*) FROM books UNION SELECT adress,count(*) FROM inbooks UNION SELECT adress,count(*) FROM inproceedings GROUP BY adress");
    $stmt->execute();
    $location=$stmt->get_result()->fetch_array();
    $stmt = $mysqli->prepare("SELECT author1,author2,author3,author4 FROM posts");
    $stmt->execute();
    $result = $stmt->get_result();
    $authors=array();
     while($row2 = $result->fetch_assoc()) {
       $y=0;
       if($row2['author1']!="")
    $y++;
    if($row2['author2']!="")
    $y++;
    if($row2['author3']!="")
    $y++;
    if($row2['author4']!="")
    $y++;
    $authors[]=$y;
     }
    
    $average=array_sum($authors)/count($authors);
    $object = (object) ['totalposts' => $row[0],
    'articleposts'=>$row[1],
    'inprocposts'=>$row[2],
    'bookposts'=>$row[3],
    'inbookposts'=>$row[4],
    'yearposts'=>$columchart,
    'mindateposts'=>$postdates[0],
    'maxdateposts'=>$postdates[1],
    'mindatearticles'=>$articledates[0],
    'maxdatearticles'=>$articledates[1],
    'mindateinproc'=>$inprocdates[0],
    'maxdateinproc'=>$inprocdates[1],
    'mindatebooks'=>$bookdates[0],
    'maxdatebooks'=>$bookdates[1],
    'mindateinbooks'=>$inbookdates[0],
    'maxdateinbooks'=>$inbookdates[1],
    'publishers'=>$pubs,
    'location'=>$location,
    'avg'=>$average
  ];
    return json_encode($object) ;
}
else 
{
  $object = (object) ['totaluserposts' =>"",
      'totaluserarticles'=>"",
      'totaluserinproceedings'=>"",
      'totaluserbooks'=>"",
      'totaluserinbooks'=>"",
      'mindateuser'=>"",
      'maxdateuser'=>""
     ];
  
 $stmt=$mysqli->prepare("SELECT 
 (SELECT count(*) FROM posts WHERE username=?) AS total, 
 (SELECT COUNT(*) FROM article,posts WHERE postid=posts.id AND posts.username=?) AS artcls, 
 (SELECT COUNT(*) FROM inproceedings,posts WHERE postid=posts.id AND posts.username=?) AS inprocs, 
 (SELECT COUNT(*) FROM books,posts WHERE postid=posts.id AND posts.username=?) AS boo,
 (SELECT COUNT(*) FROM inbooks,posts WHERE postid=posts.id AND posts.username=?) AS inbo");
  $stmt->bind_param("sssss", $_SESSION['username'], $_SESSION['username'], $_SESSION['username'], $_SESSION['username'], $_SESSION['username']);
  $stmt->execute();
  $result = $stmt->get_result();   
     while($row = $result->fetch_assoc()) {
          $object->totaluserposts=$row['total'];
          $object->totaluserarticles=$row['artcls'];
           $object->totaluserinproceedings=$row['inprocs'];
            $object->totaluserbooks=$row['boo'];
            $object->totaluserinbooks=$row['inbo'];
     }

  $stmt2=$mysqli->prepare("SELECT min(date),max(date) FROM posts WHERE username=?");
  $stmt2->bind_param("s", $_SESSION['username']);
  $stmt2->execute();
  $result2= $stmt2->get_result();   
     while($row2 = $result2->fetch_assoc()) {
       $object->mindateuser=$row2['min(date)'];
       $object->maxdateuser=$row2['max(date)'];
     }

     return json_encode($object);
     
    }


   
  }
  function importbib()
  {
    
   if(isset($_FILES['file']['tmp_name']))  {
     try{
   $listener = new Listener();
//$listener->addProcessor(new Processor\TagNameCaseProcessor(CASE_LOWER));
$listener->addProcessor(new Processor\KeywordsProcessor());
$parser = new Parser();
$parser->addListener($listener);

// Parse the content, then read processed data from the Listener
$parser->parseFile($_FILES['file']['tmp_name']); // or parseFile('/path/to/file.bib')
$entries = $listener->export();
$mysqli = mysqli_connect("localhost", "root", "", "annapp");
for($i=0;$i<count($entries);$i++)
{
  $pieces=explode(',',$entries[$i]['author']);
  $author1="";
  $author2="";
  $author3="";
  $author4="";
  if(isset($pieces[0]))
  $author1=$pieces[0];
  if(isset($pieces[1]))
  $author2=$pieces[1];
  if(isset($pieces[2]))
  $author3=$pieces[2];
  if(isset($pieces[3]))
  $author4=$pieces[3];
    
  if($entries[$i]['_type']=="article")
  {
    $stmt = $mysqli->prepare("INSERT INTO posts(username,title,note,url,author1,author2,author3,author4) VALUES (?, ?,?,?,?, ?,?,?)");
    $stmt->bind_param("ssssssss",$_SESSION['username'],$entries[$i]['title'],$entries[$i]['note'],$entries[$i]['url'],$author1,$author2,$author3,$author4);
    $stmt->execute();
    $stmt = $mysqli->prepare("INSERT INTO article(articlename,volume,number,pages,document,postid) VALUES (?, ?,?,?,?,(SELECT id From posts WHERE title=?))");
    $stmt->bind_param("ssssss",$entries[$i]['articlename'] ,$entries[$i]['volume'] ,$entries[$i]['number'] ,$entries[$i]['pages'] ,$entries[$i]['document'] ,$entries[$i]['title'] );
    $stmt->execute();
    $stmt->close();
  }
  else if($entries[$i]['_type']=="inproceeding")
  {
    $stmt = $mysqli->prepare("INSERT INTO posts(username,title,note,url,author1,author2,author3,author4) VALUES (?, ?,?,?,?, ?,?,?)");
    $stmt->bind_param("ssssssss",$_SESSION['username'],$entries[$i]['title'],$entries[$i]['note'],$entries[$i]['url'],$author1,$author2,$author3,$author4);
    $stmt->execute();
    $stmt = $mysqli->prepare("INSERT INTO inproceedings(booktitle,editor,series,volume,number,pages,organisation,publisher,adress,document,postid) VALUES (?, ?,?,?,?,?,?,?,?,?,(SELECT id From posts WHERE title=?))");
    $stmt->bind_param("sssssssssss",$entries[$i]['booktitle'], $entries[$i]['editor'],$entries[$i]['series'],$entries[$i]['volume'],$entries[$i]['number'],$entries[$i]['pages'],$entries[$i]['organisation'],$entries[$i]['publisher'],$entries[$i]['adress'],$entries[$i]['document'],$entries[$i]['title']);
    $stmt->execute();
    $stmt->close();
  }
  else if($entries[$i]['_type']=="book")
  {
    $stmt = $mysqli->prepare("INSERT INTO posts(username,title,note,url,author1,author2,author3,author4) VALUES (?, ?,?,?,?, ?,?,?)");
    $stmt->bind_param("ssssssss",$_SESSION['username'],$entries[$i]['title'],$entries[$i]['note'],$entries[$i]['url'],$author1,$author2,$author3,$author4);
    $stmt->execute();
    $stmt = $mysqli->prepare("INSERT INTO books(publisher,volume,number,series,adress,edition,isbn,issn,postid) VALUES (?,?,?,?,?,?,?,?,(SELECT id From posts WHERE title=?))");
    $stmt->bind_param("sssssssss",$entries[$i]['publisher'],$entries[$i]['volume'],$entries[$i]['number'],$entries[$i]['series'],$entries[$i]['adress'],$entries[$i]['edition'],$entries[$i]['isbn'],$entries[$i]['issn'],$entries[$i]['title']);
    $stmt->execute();
    $stmt->close();
  }
  else if($entries[$i]['_type']=="inbook")
  {
    $stmt = $mysqli->prepare("INSERT INTO posts(username,title,note,url,author1,author2,author3,author4) VALUES (?, ?,?,?,?, ?,?,?)");
    $stmt->bind_param("ssssssss",$_SESSION['username'],$entries[$i]['title'],$entries[$i]['note'],$entries[$i]['url'],$author1,$author2,$author3,$author4);
    $stmt->execute();
    $stmt = $mysqli->prepare("INSERT INTO inbooks(publisher,adress,chapter,pages,volume,number,series,type,edition,isbn,issn,postid) VALUES (?,?,?,?,?,?,?,?,?,?,?,(SELECT id From posts WHERE title=?))");
    $stmt->bind_param("ssssssssssss",$entries[$i]['publisher'], $entries[$i]['adress'],$entries[$i]['chapter'],$entries[$i]['pages'],$entries[$i]['volume'],$entries[$i]['number'],$entries[$i]['series'],$entries[$i]['type'],$entries[$i]['edition'],$entries[$i]['isbn'],$entries[$i]['issn'],$entries[$i]['title']);
    $stmt->execute();
    $stmt->close();
  

  }
}
} catch(Exception $e) {
  if($mysqli->errno === 1062) echo '<script type="text/JavaScript">alert("Article already exists.");</script>' ; 
}
  echo '<script type="text/JavaScript">alert("Post set.");window.location.href = "index.html";</script>' ; 
}
else
echo '<script type="text/JavaScript">alert("No file was found");window.location.href = "index.html";</script>' ; 
   }

  if (isset($_POST['calluser'])) {
     echo usercheck();
 }else if(isset($_POST['logout']))
 {
      echo logout();
 }
 else if(isset($_POST['profstuff']))
 {
     if(isset($_SESSION['username']))
      echo getprofstuff();
      else
      die;
 }
    else if(isset($_POST['userposts']))
    echo getuserposts();
    else if(isset($_POST['action']) && $_POST['action']=='update')
    echo updatepost();
    else if(isset($_POST['action']) && $_POST['action']=='delete')
    echo deletepost();
    else if(isset($_POST['exportposts']))
    echo exportpost();
    else if(isset($_POST['stats']))
    echo getstats();
    else if(isset($_POST['import']))
     echo importbib();
  
   ?>