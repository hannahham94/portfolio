<?php
/*This file will modify the book, author, books category, book series, and order details
It will create a header and a drop down box which will load the specific modify when it is selected.
alot of errors are ignored so dont remove the error reporting.
There are tons of hidden inputs because js doesn't like to communicate with php so they are like bridges*/
error_reporting(0);				//Do not remove
	ini_set('display_errors', 0); //Hides errors caused by $check

$idType = explode("?",$_SERVER['REQUEST_URI']); 		
$idType = explode("=",$idType[1]);				//this gets the specific id type from the url so that the pages will load the information on load
$id = $_GET[$idType[0]];						//id type (isbn , authorID , etc...)
$form = $_GET["form"];							//form type (author, book, series, etc...)
$read = "readonly";
$dis = "disabled";
$org=$_GET["organization"];
$org=str_replace(" ","+",$org);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookstore";
// Create connection
$link = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$link) {
	die("Connection failed: " . mysqli_connect_error());
}

$customerID = 0;
$sql = "SELECT * FROM loggedIn";
$result = $link->query($sql);
if ($result->num_rows > 0) {
	// output data of each row
	while($row = $result->fetch_assoc()) {
		$customerID = $row['customerID'];
	}
}
if($customerID < 3000 and $customerID > 1999)
{
	$sql = "SELECT * FROM loggedIn INNER JOIN customer ON loggedIn.customerID = customer.customerID";
	$result = $link->query($sql);
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			$customerName = $row['first'] . " " . $row['last'];
			$type = "customer";
		}
	}
}
elseif($customerID < 4000 and $customerID > 2999)
{
	$sql = "SELECT * FROM loggedIn INNER JOIN credentials ON loggedIn.customerID = credentials.customerID";
	$result = $link->query($sql);
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			$customerName = $row['username'];
			$type = "admin";
		}
	}
}
else
{
	$customerName = "Login/Sign Up";
	$type = null;
}
?>

<html>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css" />
<link rel="stylesheet"
href="https://fonts.googleapis.com/css?family=Lobster" />
<style>
.w3-Lobster {
  font-family: 'Lobster', serif;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<head>
		<title>Books 'N Gerbils</title>
		<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
		<script>
			function clickBox(){
				if(document.getElementById("optionbox").value != ""){
					$("#load").load(document.getElementById("optionbox").value + ".php?id=&form=");
				}
			}
			function loadBox(){
				if(document.getElementById("form").value != "supplierrep"){
					$("#load").load(document.getElementById("form").value + ".php?" + document.getElementById("idType").value +
					"=" + document.getElementById("id").value + "&form="); 				//When the form is submitted, this is called so that the correct form can load with the inputted id.
				}
				else{
					$("#load").load("supplierrep.php?" + document.getElementById("idType").value + "=" + document.getElementById("id").value + "&form=&first=" + document.getElementById("first").value + "&last=" + document.getElementById("last").value + "&organization=" + document.getElementById("org").value);
				}
				
			}
		</script>
	</head>
	<body onload="loadBox()">
		
			<header>
			<div>
				<h1>Books 'N Gerbils</h1>
				
				<ul>
					<li><a id = "loginLink" href="log_in.php"><?php echo $customerName;?></a></li>
					<li><a href="../index.php">Home</a></li>
					<li><a href="../search.php?series=&title=&author=">Search</a></li>
					<li><a href="../browse.php?genre=&arrange=">Browse</a></li>
					<li><a href="cart.php">Cart</a></li>
				</ul>
			</div>
		</header>
		
			<main style = "margin-top: 100;
					text-align: center;
					background: #E2BE9E;
					margin-left: 30;
					margin-right: auto;
					width: 95%;
					height: 60px;
					border-style: dashed;
					border-radius: 7px;
					border-color: #7C1313;
					display:inline-block;">
				<input id="id" value ="<?php echo $id;?>" hidden />					<!--gets the id from the php so that the form can be loaded with the info-->
				<input id="form" value ="<?php echo $form;?>" hidden />				<!--Gets the correct form to be loaded from php-->
				Modify: <select id = "optionbox" style= "width: 200;
								background: #E2BE9E;
								border: 2px solid #7C1313;
								border-radius: 7px;">
							<option value=""></option>
							<option value="book">Book</option>
							<option value="author">Author</option>
							<option value="assigned">Genre</option>
							<!--<option value="publisher">Publisher</option>-->
							<option value="series">Series</option>
							<option value="orders">Order</option>
							<option value="customer">Customer</option>
							<option value="credentials">Login info</option>
							<option value="supplierrep">Supplier Rep</option>
							<!--<option value="contactDetails">Contact Details</option>-->
					</select>
					<input id="idType" value="<?php echo $idType[0];?>" hidden />
					<input onclick="clickBox()" type = "submit" id= "button" name = "modify" value="Modify DB" 
				style="margin-left: 5px;
					   margin-top: 13px;
					   color: #E2BE9E; border-color: #7C1313; background: #7C1313; border-radius: 7px;" />
				<div id="load"></div>
		</main>
	</body>
	
</html>
<script>
	changeHREF();
	function changeHREF()
	{
		var type = "<?php echo $type?>";
		if(type === "customer")
		{
			$('#loginLink').attr('href', 'customerProfile.php');
		}
		else if(type === "admin")
		{
			$('#loginLink').attr('href', 'admin.php');
		}
		else
		{
			$('#loginLink').attr('href', 'log_in.php');
		}
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