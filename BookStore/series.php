<?php
/* This is mostly done and barely tested. It worked on 12/1/19*/
error_reporting(0);
	ini_set('display_errors', 0); //Hides errors caused by $check
$link = mysqli_connect("localhost", "root", "", "bookstore");
$read = "readonly";
$isbn = $_GET["isbn"];
$dis = "disabled";
$form = $_GET["form"];
$cc=""; $c="";
if($isbn != ""){
	$sql="SELECT * FROM series WHERE ISBN = '$isbn';";
	$result = mysqli_query($link,$sql);
	if($row = mysqli_fetch_array($result)) {
		$cc = $row[1];
		$read="";
		$dis="";
	}
	else{
		$isbn="That isbn does not exist.";
	}
	
}
	//$sql = "SELECT * FROM assigned,category WHERE ISBN = '$isbn' AND assigned.ISBN = category.categoryCode;";
	//$result = mysqli_query($link,$sql);
	//if($row = mysqli_fetch_array($result)) {
?>

<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">
<link rel="stylesheet"
href="https://fonts.googleapis.com/css?family=Lobster">
<style>
.w3-Lobster {
  font-family: 'Lobster', serif;
}
</style>
<form action="modifyindex.php?id=&form=">
		<div style="margin-top: 25;
				text-align: center;
				background: #E2BE9E;
				margin-left: 30;
				margin-right: 15%;
				width: 95%;
				height: 250px;
				border-style: dashed;
				border-radius: 7px;
				border-color: #7C1313;
				display:inline-block;">
				
				<br />
				<br />
				ISBN: <input type="text" id="isbnbox" name="isbn" value="<?php echo $isbn;?>" /><br/>
				<input type="text" id="form" name="form" value="series" hidden />
				<br />
				Series Name: <input type="text" id="cc" value="<?php echo $cc;?>" <?php echo $read;?> /> <br /><br />
				<input type = "submit" value="Get Record" style="color: #E2BE9E; border-color: #7C1313; background: #7C1313; border-radius: 4px;">
				<input type = "submit" value="Delete Record" style="margin-left: 40px; color: #E2BE9E; border-color: #7C1313; background: #7C1313; border-radius: 4px;" disabled />
				<input type = "submit" formaction="modify.php" id="mod" value="Modify Record" 
				style="margin-left: 40px; color: #E2BE9E; border-color: #7C1313; background: #7C1313; border-radius: 4px;" disabled />
				
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

<script type="text/javascript">
	document.getElementById("isbnbox").onclick = function(){
		document.getElementById("isbnbox").focus(); document.getElementById("idbox").select();
	}
	document.getElementById("isbnbox").oninput = function(){	//if the isbn is changed, it cant modify untill it is realoaded.
			if(document.getElementById("idcheck").value != document.getElementById("isbnbox").value){
				document.getElementById("mod").disabled=true;
				
			}
	}
	document.getElementById("cc").oninput = function(){
		document.getElementById("cc").name="seriesName";
		document.getElementById("mod").disabled=false;
	}
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