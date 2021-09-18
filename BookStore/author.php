<?php
error_reporting(0);
	ini_set('display_errors', 0); //Hides errors caused by $check
$link = mysqli_connect("localhost", "root", "", "bookstore");
$read = "readonly"; 
$dis = "disabled";
$id = $_GET["authorID"];
$form = $_GET["form"];
$first=""; $last=""; $gender=""; $year=""; $month=""; $day="";
if($id != ""){
	$sql="SELECT * FROM author WHERE authorID = '$id';";
	$result = mysqli_query($link,$sql);
	
	if($row = mysqli_fetch_array($result)) {
		$first=$row["first"];$last=$row["last"];$gender=$row["gender"];
		$date=$row["DoB"];
		$date=explode("/",$date);
		if(strlen($date[0]) == 1 ){
			$date[0]="0".$date[0];
		}
		if(strlen($date[1]) == 1 ){
			$date[1]="0".$date[1];
		}
		$cDate=$date[2] . "-" . $date[0] . "-" . $date[1];
		$dis="";
		$read="";
	}
	else{
		$id="That ID does not exist.";
	}
	
}

?>

<form action="modifyindex.php?id=&form=">
			<div style="margin-top: 40; text-align: center; background: #E2BE9E;
				margin-left: 30;
				margin-right: 15%;
				width: 95%;
				height: 350px;
				border-style: dashed;
				border-radius: 7px;
				border-color: #7C1313;
				display:inline-block;" >
				<br />
				<br />
				
				Author ID: <input type="text" name = "authorID" id= "idbox" value="<?php echo $id;?>" />
				<input name="form" value="author" hidden />
				<br/>			
				<br/>
				
				First: <input type="text" id="first" value="<?php echo $first;?>" <?php echo $read;?> />
				Last: <input type="text" id="last" value="<?php echo $last;?>" <?php echo $read;?> /><br/>
				<br />
				Gender: <input type="text" id="gender" value="<?php echo $gender;?>" <?php echo $read;?> /><br/>
				<br />
				DoB: <input type="date" id="dob" value="<?php echo $cDate;?>"/>
				<br/>
				
				<br />
				<br />
				<input type = "submit" value="Get Record" style="color: #E2BE9E; border-color: #7C1313; background: #7C1313; border-radius: 4px;"/>
				<input type = "submit" formaction="delete.php" value="Delete Record" style="margin-left: 40px;
				color: #E2BE9E; border-color: #7C1313; background: #7C1313; border-radius: 4px;" <?php echo $dis;?> />
				<input type = "submit" id="mod" formaction="modify.php" value="Modify Record" style="margin-left: 40px;
				color: #E2BE9E; border-color: #7C1313; background: #7C1313; border-radius: 4px;" disabled=true />
				
				<!--<input type = "submit" name = "addDuplicate" value="Add Author with Same Name" style="margin-left: 40px;">
				-->
	</div>
</form>
<script>
	document.getElementById("idbox").onclick = function(){
		document.getElementById("idbox").focus(); document.getElementById("idbox").select();
	}
	document.getElementById("idbox").oninput = function(){	//if the isbn is changed, it cant modify untill it is realoaded.
			if(document.getElementById("idcheck").value != document.getElementById("idbox").value){
				document.getElementById("mod").disabled=true;
				
			}
	}
	document.getElementById("del").onclick= function(){
		document.getElementById("del").name="del";
		document.getElementById("del").value="author,";
	}
	document.getElementById("first").oninput = function(){
		document.getElementById("first").name="first";
		document.getElementById("mod").disabled=false;
	}
	document.getElementById("last").oninput = function(){
		document.getElementById("last").name="last";
		document.getElementById("mod").disabled=false;
	}
	document.getElementById("gender").oninput = function(){
		document.getElementById("last").name="gender";
		document.getElementById("mod").disabled=false;
	}
	document.getElementById("dob").oninput = function(){
		document.getElementById("mod").disabled=false;
		document.getElementById("dob").name="DoB";
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
	margin-left: 1%;
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