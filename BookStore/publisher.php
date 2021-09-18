<?php
$link = mysqli_connect("localhost", "root", "", "bookdb");

//variable to track what to modify
$modify = "publisher";


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
				height: 415px;
				border-style: dashed;
				border-radius: 7px;
				border-color: #7C1313;
				display:inline-block;">
				
				<br />
				<br />
				<div>
				<input type = "submit" name = "get" value="Get Record" style="color: #E2BE9E; border-color: #7C1313; background: #7C1313; border-radius: 4px;">
				<input type = "submit" name = "delete" value="Delete Record" style="margin-left: 40px;
				color: #E2BE9E; border-color: #7C1313; background: #7C1313; border-radius: 4px;">
				<input type = "submit" name = "modify" value="Modify Record" style="margin-left: 40px;
				color: #E2BE9E; border-color: #7C1313; background: #7C1313; border-radius: 4px;">
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

<script type="text/javascript">
	function setUrl() {
		window.location.href = 'modify' + document.getElementById('optionbox').value + '.php';
	};
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