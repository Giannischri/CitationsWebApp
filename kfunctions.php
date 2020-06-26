<?php 
    session_start();
	class article{
		public $articlename="";
		public $article_volume="";
		public $article_number="";
		public $article_pages="";
		public $article_document="";
	}
	class books{
		public $book_publisher="";
		public $book_volume="";
		public $book_number="";
		public $book_series="";
		public $book_adress="";
		public $book_edition="";
		public $book_isbn="";
		public $book_issn="";
	}
	class inbooks{
		public $inb_publisher="";
		public $inb_adress="";
		public $inb_chapter="";
		public $inb_pages="";
		public $inb_volume="";
		public $inb_series="";
		public $inb_type="";
		public $inb_number="";
		public $inb_edition="";
		public $inb_isbn="";
		public $inb_issn="";
	}
	class inproceedings{
		public $inp_booktitle="";
		public $inp_editor="";
		public $inp_series="";
		public $inp_volume="";
		public $inp_number="";
		public $inp_pages="";
		public $inp_organisation="";
		public $inp_publisher="";
		public $inp_adress="";
		public $inp_document="";
	}
	class user{
		public $username="";
		public $role="";
		public $numberofposts="";
		public $date="";
		
	}
    
    function admin(){
        $mysqli = mysqli_connect("localhost", "root", "", "annapp");
        $stmt = $mysqli->prepare("SELECT username,role FROM user WHERE username = ?");
        $stmt->bind_param("s", $_SESSION['username']);
        $stmt->execute();
        $result = $stmt->get_result();
        $return="";
        while($row = $result->fetch_assoc()) {
            if($row['role']=="admin"){
                $return="admin";
			}
		}
        if($return==""){
            $return="noadmin";
		}
        $stmt->close();
        return json_encode($return);
	}
    function admin_add(){
        $mysqli = mysqli_connect("localhost", "root", "", "annapp");
        $username=$_POST['username'];
        $password=$_POST['password'];
        $role=$_POST['role'];
        $stmt = $mysqli->prepare("INSERT INTO `user`(`username`, `password`, `role`) VALUES ('$username','$password','$role')");
        $stmt->execute();
        //$result = $stmt->get_result();
        $return="ok";
        $stmt->close();
        return json_encode($username);
	}
    
    function admin_delete(){
        $mysqli = mysqli_connect("localhost", "root", "", "annapp");
        $username=$_POST['username'];
        $stmt = $mysqli->prepare("DELETE FROM `user` WHERE username = '$username'");
        $stmt->execute();
        $return="ok";
        $stmt->close();
        
        return json_encode($username);
	}
    function admin_modify(){
        $mysqli = mysqli_connect("localhost", "root", "", "annapp");
        $stmt = $mysqli->prepare("SELECT * FROM `user`");
        $stmt->execute();
        $return=array();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()){
            $return[]=array("username" => $row['username'],
            "password" => $row['password'],
            "role" => $row['role']);
		}
        $stmt->close();
        return json_encode($return);
	}
    function admin_modify_author(){
        $mysqli = mysqli_connect("localhost", "root", "", "annapp");
        $stmt = $mysqli->prepare("SELECT * FROM `authors");
        $stmt->execute();
        $return=array();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()){
            $return[]=array("username" => $row['username'],
            "name" => $row['name'],
            "surname" => $row['surname'],
            "orcidurl" => $row['orcidurl'],
            "url" => $row['url'],
            "specialty" => $row['specialty']);
		}
        $stmt->close();
        return json_encode($return);
	}
    function admin_modify_last(){
        $mysqli = mysqli_connect("localhost", "root", "", "annapp");
        $stmt = $mysqli->prepare("SELECT * FROM `user`");
        $stmt->execute();
        $return=array();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()){
            $return[]=array("username" => $row['username'],
            "password" => $row['password'],
            "role" => $row['role']);
		}
        //$length=mysqli_num_rows($result);
        //for($i=1;$i<=$length;$i++){
        // if($object[i].username!=$check[i].username){
        //   $last[]=array("username" => $object[i].username,
        //   "password" => $object[i].password,
        //   "role" => $object[i].role);
        //}
        //}
        
        $stmt->close();
        return json_encode("suckerrrr");
	}
    
	function keyup(){
		$conn = mysqli_connect("localhost", "root", "", "annapp");
		$inpText=$_POST['query'];
		//gia articles
		$query="SELECT articlename FROM article WHERE articlename LIKE '%$inpText%'"; 
		$result=$conn->query($query);
		if($result->num_rows>0){
			while($row=$result->fetch_assoc()){
				echo "<a href='#' class='list-group-item list-group-item-action border-1'>".$row['articlename']."</a>";
			}
		}
		else{
			echo "<a class='list-group-item border-1'>No Record for article</a>";
		}
		//gia books
		$query="SELECT publisher FROM books WHERE publisher LIKE '%$inpText%'"; 
		$result=$conn->query($query);
		if($result->num_rows>0){
			while($row=$result->fetch_assoc()){
				echo "<a href='#' class='list-group-item list-group-item-action border-1'>".$row['publisher']."</a>";
			}
		}
		else{
			echo "<a class='list-group-item border-1'>No Record for books</a>";
		}
		//gia posts
		$query="SELECT title FROM posts WHERE username LIKE '%$inpText%' OR title LIKE '%$inpText%'"; 
		$result=$conn->query($query);
		if($result->num_rows>0){
			while($row=$result->fetch_assoc()){
				echo "<a href='#' class='list-group-item list-group-item-action border-1'>".$row['title']."</a>";
			}
		}
		else{
			echo "<a class='list-group-item border-1'>No Record for posts</a>";
		}
		
		//gia inbooks
		$query="SELECT publisher FROM inbooks WHERE publisher LIKE '%$inpText%' OR isbn LIKE '%$inpText%' OR issn LIKE '%$inpText%'"; 
		$result=$conn->query($query);
		if($result->num_rows>0){
			while($row=$result->fetch_assoc()){
				echo "<a href='#' class='list-group-item list-group-item-action border-1'>".$row['publisher']."</a>";
			}
		}
		else{
			echo "<a class='list-group-item border-1'>No Record for inbooks</a>";
		}
		
		//gia inproceedings
		$query="SELECT booktitle FROM inproceedings WHERE booktitle LIKE '%$inpText%' OR editor LIKE '%$inpText%' OR publisher LIKE '%$inpText%'"; 
		$result=$conn->query($query);
		if($result->num_rows>0){
			while($row=$result->fetch_assoc()){
				echo "<a href='#' class='list-group-item list-group-item-action border-1'>".$row['booktitle']."</a>";
			}
		}
		else{
			echo "<a class='list-group-item border-1'>No Record for inproceedings</a>";
		}
		
		//gia user
		$query="SELECT username FROM user WHERE username LIKE '%$inpText%' "; 
		$result=$conn->query($query);
		if($result->num_rows>0){
			while($row=$result->fetch_assoc()){
				echo "<a href='#' class='list-group-item list-group-item-action border-1'>".$row['username']."</a>";
			}
		}
		else{
			echo "<a class='list-group-item border-1'>No Record for user</a>";
		}	
	}
	
	function search(){
		$data=$_POST['query2'];
		$return=array();
		$conn=mysqli_connect("localhost","root","","annapp");
		//an einai sta inbooks
		$stmt=$conn->prepare("SELECT * FROM inbooks WHERE publisher = '$data' OR isbn= '$data' OR issn = '$data' OR type='$data'");
		$stmt->execute();
		$result = $stmt->get_result();
		$return[]=1;
		while($row = $result->fetch_assoc()){
			$a=new inbooks();
			$a->inb_publisher=$row['publisher']; 
			$a->inb_adress=$row['adress']; 
			$a->inb_chapter=$row['chapter']; 
			$a->inb_pages=$row['pages']; 
			$a->inb_volume=$row['volume']; 
			$a->inb_number=$row['number']; 
			$a->inb_series=$row['series']; 
			$a->inb_type=$row['type']; 
			$a->inb_edition=$row['edition']; 
			$a->inb_isbn=$row['isbn']; 
			$a->inb_issn=$row['issn']; 
			$return[]=$a;
		}
		$stmt->close();
		//an einai sta inproceedings
		$stmt=$conn->prepare("SELECT * FROM inproceedings WHERE booktitle = '$data' OR publisher = '$data'");
		$stmt->execute();
		$result = $stmt->get_result();
		$return[]=2;
		while($row = $result->fetch_assoc()){
			$a=new inproceedings();
			$a->inp_booktitle=$row['booktitle']; 
			$a->inp_editor=$row['editor']; 
			$a->inp_series=$row['series']; 
			$a->inp_volume=$row['volume']; 
			$a->inp_number=$row['number']; 
			$a->inp_pages=$row['pages']; 
			$a->inp_organisation=$row['organisation']; 
			$a->inp_publisher=$row['publisher']; 
			$a->inp_adress=$row['adress']; 
			$a->inp_document=$row['document']; 
			$return[]=$a;
		}
		$stmt->close();
		
		//gia article
		$stmt=$conn->prepare("SELECT * FROM article WHERE articlename = '$data'");
		$stmt->execute();
		$result = $stmt->get_result();
		$return[]=3;
		while($row = $result->fetch_assoc()){
			$a=new article();
			$a->articlename=$row['articlename']; 
			$a->article_volume=$row['volume']; 
			$a->article_number=$row['number']; 
			$a->article_pages=$row['pages'];
			$a->article_document=$row['document'];
			$return[]=$a;
		}
		$stmt->close();
		//an einai sta books
		$stmt=$conn->prepare("SELECT * FROM books WHERE publisher = '$data' OR isbn= '$data' OR issn = '$data'");
		$stmt->execute();
		$result = $stmt->get_result();
		$return[]=4;
		while($row = $result->fetch_assoc()){
			$a=new books();
			$a->book_publisher=$row['publisher']; 
			$a->book_volume=$row['volume']; 
			$a->book_number=$row['number']; 
			$a->book_series=$row['series']; 
			$a->book_adress=$row['adress']; 
			$a->book_edition=$row['edition']; 
			$a->book_isbn=$row['isbn']; 
			$a->book_issn=$row['issn']; 
			$return[]=$a;
			
		}
		$stmt->close();
		
		return json_encode($return);
		
	}
	
	
	//gia emfanish pinakwn sthn selida
	if(isset($_POST['query2'])){
		echo search();
	}
    else if(isset($_POST['adminshit'])){
        echo admin();
	}
    else if(isset($_POST['admin-add'])){
        echo admin_add();
	}
    else if(isset($_POST['admin-delete'])){
        echo admin_delete();
	}
    else if(isset($_POST['admin-modify'])){
        echo admin_modify();
	}
    else if(isset($_POST['admin-modify-author'])){
        echo admin_modify_author();
	}
    else if(isset($_POST['admin-modify-last'])){
        echo admin_modify_last();
	}
	else if(isset($_POST['query'])){
		echo keyup();
	}
	
?> 
