<?php
$link = mysqli_connect("localhost", "root", "", "bookstore");

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
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lobster">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<style>
.w3-Lobster {
  font-family: 'Lobster', serif;
}
</style>

	<head>
		<title>Books 'N Gerbils</title>
	</head>
	<body onload="openForm();">
		<header>
			<div>
				<img src="logo.png" style= "height: 70px; width 70px;"><h1>Books 'N Gerbils</h1>
				
				<ul>
					<li><a id = "loginLink" href="log_in.php"><?php echo $customerName;?></a></li>
					<li><a href="index.php">Home</a></li>
					<li><a href="search.php?series=&title=&author=">Search</a></li>
					<li><a href="browse.php?genre=&arrange=">Browse</a></li>
					<li><a href="cart.php">Cart</a></li>
				</ul>
			</div>
		</header>
		
		<main>
		
			<div style = "margin-top: 100;
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
				<ul>
					<li><a href="insert.php"><b>Add to Database</b></a></li>
					<li><a href="modifyindex.php?id=&form="><b>Modify Database</b></a></li>
					<li><a href="display.php"><b>Display Information</b></a></li>
					<li><a href="query.php"><b>Modify With Direct SQL</b></a></li>
					<input type="submit" id="logoutButton" value="Logout" onclick="logout()"
					style = "background: #7C1313; border: 1px solid #7C1313; border-style: outset; color: #E2BE9E; border-radius: 7px; margin-left: 10px;">
				</ul>
			</div>
		</main>
		
	</body>
	
</html>

<script>
function logout()
	{
		$.ajax({
			type: "POST",
			url: "logout.php",
			data: "",
			success:  function(data) {
				//location.reload();
				window.location = "log_in.php";
			},
			error: function() {
				alert("failure");
			}
			});
	}
changeHREF();
		function changeHREF()
	{
		var type = "<?php echo $type?>";
		if(type === "")
		{
			window.location = "log_in.php";
		}
		else if(type === "customer")
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

main li {
	text-decoration: none;
	display: inline-block;
	margin-left: 20px;
	margin-right: 60px;
}

main a {
	text-decoration: none;
	text: bold;
	font-family: serif;
	font-size: 20px;
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