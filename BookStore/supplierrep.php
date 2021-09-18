<?php
error_reporting(0);
	ini_set('display_errors', 0); //Hides errors caused by $check
$link = mysqli_connect("localhost", "root", "", "bookstore");
$read = "readonly"; 
$dis = "disabled";
$id = $_GET["ID"];
$form = $_GET["form"];
$first=$_GET["first"]; $last=$_GET["last"]; $organization=str_replace("+"," ",$_GET["organization"]);
$email=""; $cellNumber="";$workNumber="";$sName="";
if($id != ""){
	$sql="SELECT * FROM supplierrep WHERE ID = $id AND first='$first' AND last='$last' AND organization='$organization';";
	$result = mysqli_query($link,$sql);
	if($row = mysqli_fetch_array($result)) {
		$email=$row["email"];$cellNumber=$row["cellNumber"];$workNumber=$row["workNumber"];
		$sName=$row["sName"];
		$dis="";
		$read="";
	}
	else{
		$id="That ID does not exist.";
		$first="";
		$last="";
		$organization="";
	}
	
}

?>

<form action="modifyindex.php">
			<div style="margin-top: 25; text-align: center; background: #E2BE9E;
				margin-left: 30;
				margin-right: 15%;
				width: 95%;
				height: 550px;
				border-style: dashed;
				border-radius: 7px;
				border-color: #7C1313;
				display:inline-block;" >
				<br />
				<br />
				<input id="idcheck" value="<?php echo $isbn;?>" hidden />
				Rep. ID: <input type="text" name = "ID" id= "idbox" value="<?php echo $id;?>" required=true />
				<input name="form" value="supplierrep" hidden />
				<br/><br />
				First Name: <input type="text" name="first" id="first" placeholder="required" value="<?php echo $first;?>" required=true /> <br /><br />
				Last Name: <input type="text" name="last" id="last" placeholder="required" value="<?php echo $last;?>" required=true /> <br/><br />
				Organization: <input type="text" name="organization" id="org" placeholder="required" value="<?php echo $organization;?>" required=true /><br /><br />
				email: <input type="text" id="email" value="<?php echo $email;?>" <?php echo $read;?>/><br /><br />
				cell phone: <input id="cp" value="<?php echo $cellNumber;?>" <?php echo $read;?>/><br /><br />
				work number: <input id="wn" value="<?php echo $workNumber;?>" <?php echo $read;?> /><br /><br />
				Supplier Name: <input id="sname" value="<?php echo $sName;?>" <?php echo $read;?> />
				<br/>
				
				<br />
				<br />
				<input type = "submit" value="Get Record" style="color: #E2BE9E; border-color: #7C1313; background: #7C1313; border-radius: 4px;"/>
				<input type = "submit" id="del" formaction="delete.php" value="Delete Record" style="margin-left: 40px;
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
	document.getElementById("email").oninput = function(){
		document.getElementById("email").name="email";
		document.getElementById("mod").disabled=false;
	}
	document.getElementById("cp").oninput = function(){
		document.getElementById("cp").name="cellNumber";
		document.getElementById("mod").disabled=false;
	}
	document.getElementById("wn").oninput = function(){
		document.getElementById("wn").name="workNumber";
		document.getElementById("mod").disabled=false;
	}
	document.getElementById("sname").oninput = function(){
		document.getElementById("mod").disabled=false;
		document.getElementById("sname").name="sName";
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