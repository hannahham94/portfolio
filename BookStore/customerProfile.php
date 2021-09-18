<?php
	$servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bookstore";
    
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "SELECT * FROM loggedIn INNER JOIN customer on (customer.customerID = loggedIn.customerID)";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
			$fname = $row['first'];
			$customerID = $row['customerID'];
        }
    }
    $conn->close();
?>

<html>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet"
href="https://fonts.googleapis.com/css?family=Lobster">
<style>
.w3-Lobster {
  font-family: 'Lobster', serif;
}
</style>
	<head>
		<title>Books 'N Gerbils</title>
	</head>
	<body>
		<header>
			<div>
				<h1><a href="#">Books 'N Gerbils</a></h1>
				
				<ul>
					<li><a href="index.php">Home</a></li>
					<li><a href="search.php?series=&title=&author=">Search</a></li>
					<li><a href="browse.php?genre=&arrange=">Browse</a></li>
					<li><a href="cart.php">Cart</a></li>
				</ul>
			</div>
		</header>

		<div style="margin-top: 100px;
		text-align: center;
		background: #E2BE9E;
		margin-left: 30;
		margin-right: auto;
		width: 95%;
		height: 75%;
		border-style: dashed;
		border-radius: 7px;
		border-color: #7C1313;
		display:inline-block;">
			<h1 style="font-size:64px; font-family: Lobster, serif;">Welcome, <?php echo $fname?>!</h1>
			<input type="submit" id="logoutButton" value="Logout" onclick="logout()" style="margin-top:7%; margin-bottom:10px;
			background: #7C1313; border: 1px solid #7C1313; border-style: outset; color: #E2BE9E; border-radius: 7px;"><br>
			<input type="submit" id="editAccount" value="Edit Account" onclick="editAccount()" style="margin-bottom:10px;
			background: #7C1313; border: 1px solid #7C1313; border-style: outset; color: #E2BE9E; border-radius: 7px;"><br>
			<input type="submit" id="viewOrders" value="View Orders" onclick="viewOrders()" style="margin-bottom:10px;
			background: #7C1313; border: 1px solid #7C1313; border-style: outset; color: #E2BE9E; border-radius: 7px;"><br>
			<input type="submit" id="deleteAccount" value="Delete Account" onclick="deleteAccount()" style="margin-top:6%; margin-left:80%;
			background: #7C1313; border: 1px solid #7C1313; border-style: outset; color: #E2BE9E; border-radius: 7px;"><br>
	</div>
		
	</body>

</html>

<script>
	function editAccount()
	{
		window.location = "edit_customer_account.php";
	}
	function viewOrders()
	{
		window.location = "view_orders.php";
	}
	function deleteAccount()
	{
		$.ajax({
			type: "POST",
			url: "delete_customer.php",
			data: "customerID=" + <?php echo $customerID;?>,
			success:  function(data) {
				alert(data);
				window.location = "index.php";
			},
			error: function() {
				alert("failure");
			}
			});
	}
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
</script>

<style type="text/css">
input[type="submit"]{
	width:10%;
	height:7%;
	border-color:#E5E5E5;
	background-color:#FFFFFF;
}
input[type="submit"]:hover{
	border-color:#E5E5E5;
	background-color:#CDCDCD;
	cursor: pointer;
}
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