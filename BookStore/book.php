<?php
/*This will load information based on a books isbn. it will also allow you to delete and edit it.*/
error_reporting(0);
ini_set('display_errors', 0); //Hides errors caused by $check
$link = mysqli_connect("localhost", "root", "", "bookstore");
$read = "readonly";											//used to make some boxes readonly
$isbn = $_GET["isbn"];										//gets isbn from url
$dis = "disabled";											//disables some buttons.
$form = $_GET["form"];										//gets form type from url.
$pubDate=""; $price=""; $title=""; $userReview=""; $sName="";	//variables 
if($isbn != ""){											//if the isbn is not empty, it will try to load the id with sql
	$sql="SELECT * FROM book WHERE ISBN = '$isbn';";
	$result = mysqli_query($link,$sql);
	if($row = mysqli_fetch_array($result)) {
		$date=$row["pubDate"];$price=$row["price"];$title=$row["title"];$sName=$row["sName"]; //set variables.
		$date=explode("/",$date);
		if(strlen($date[0]) == 1 ){							//this will add a 0 infron of month and day if they are single digit.
			$date[0]="0".$date[0];							//*
		}													//*
		if(strlen($date[1]) == 1 ){							//*
			$date[1]="0".$date[1];							//*
		}													//*
		$cDate=$date[2] . "-" . $date[0] . "-" . $date[1];	//Fixes the date so that it can be loaded in a input box
		$dis="";											//makes boxes accessible
		$read="";											//*
	}
	else{											//if the isbn cant be found, this will load in the isbn box
		$isbn="That isbn does not exist.";
	}
	
}
	//$sql = "SELECT * FROM assigned,category WHERE ISBN = '$isbn' AND assigned.ISBN = category.categoryCode;";
	//$result = mysqli_query($link,$sql);
	//if($row = mysqli_fetch_array($result)) {
?>
<br />
<form action="modifyindex.php?id=&form=">
		
		<div style="margin-top: 25;
				text-align: center;
				background: #E2BE9E;
				margin-left: 30;
				margin-right: 15%;
				width: 95%;
				height: 415px;
				border-style: dashed;
				border-radius: 7px;
				border-color: #7C1313;
				display:inline-block;">
				<br />
				<br />
				ISBN: <input type="text" id="isbnbox" name="isbn" value="<?php echo $isbn;?>" /><br/>
				<input name="form" value="book" hidden />
				<br />
				Title: <input type="text" id="title" value="<?php echo $title;?>" <?php echo $read;?> /><br/>
				<br />	
				
				Price: <input type="text" id="price" value="<?php echo $price;?>" <?php echo $read;?> /><br/>
				<br />
				
				
				
				Publisher: <input type="text" id="publisher" value="<?php echo $sName;?>" <?php echo $read;?> /><br/>
				<br />
				
				Pubdate: <input type="date" id="pubDate" value="<?php echo $cDate;?>" <?php echo $read;?> /><br/>
				<br />
				<br />
				<br />
				<div>
				<input type = "submit"  value="Get Record" style="color: #E2BE9E; border-color: #7C1313; background: #7C1313; border-radius: 4px;">
				<input type = "submit" formaction="delete.php" value="Delete Record" style="margin-left: 40px;
				color: #E2BE9E; border-color: #7C1313; background: #7C1313; border-radius: 4px;" <?php echo $dis;?> />
				<input type = "submit" id="mod" formaction="modify.php" value="Modify Record" style="margin-left: 40px;
				color: #E2BE9E; border-color: #7C1313; background: #7C1313; border-radius: 4px;" disabled=true />
				</div>
				
		</div>
</form>
		<!--
		<br />
		<br />
		<div>
		<form action="" id="modifyDB">
		<input type = "delete" name = "delete" value="Delete">
		<input type = "modify" name = "modify" value="Modify">
		</form>
		</div>-->
		
		</main>
	</body>
	
</html>

<script>
	document.getElementById("isbnbox").onclick = function(){
		document.getElementById("isbnbox").focus(); 
		document.getElementById("isbnbox").select();	//This will select the entire box when
	}
	document.getElementById("isbnbox").oninput = function(){	//if the isbn is changed, it cant modify untill it is realoaded.
			if(document.getElementById("idcheck").value != document.getElementById("isbnbox").value){
				document.getElementById("mod").disabled=true;
				
			}
	}
	document.getElementById("title").oninput = function(){					//when any thing is changed, it will be added to the form so it can be modified
		document.getElementById("title").name="title";
		document.getElementById("mod").disabled=false;
	}
	document.getElementById("price").oninput = function(){
		document.getElementById("price").name="price";
		document.getElementById("mod").disabled=false;
	}
	document.getElementById("publisher").oninput = function(){
		document.getElementById("publisher").name="sName";
		document.getElementById("mod").disabled=false;
	}
	document.getElementById("pubDate").oninput = function(){
		document.getElementById("mod").disabled=false;
		document.getElementById("pubDate").name="pubDate";
	}																		//Down to here
</script>	


<style type="text/css">

body {
	background: #7A5C61;
}

header div, main {
	max-width: 980 px;
}

header {
	background: #E2BE9E;
	width: 98%;
	top: 0;
	position: fixed;
	border-style: double;
	border-width: 5px;
	border-color: #7C1313;
	margin-left: 12;
}

header h1 {
	padding-left: 15;
	float: left;
	font-family: Lobster, serif;
}

main {
	padding: 1 1 1 1;
}

header a {
	text-decoration: none;
	color: black;
}

header li {
	display: inline-block;
	margin-right: 5;
	padding-right: 20;
}

header li a:hover {
	color: #7C1313;
}

header ul {
	font-size: 20;
	float: right;
	list-style-type: none;
	padding-top: 1 em;
}

</style>