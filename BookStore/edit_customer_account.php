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
    $sql = "SELECT * FROM loggedIn INNER JOIN customer on (customer.customerID = loggedIn.customerID) INNER JOIN
    address on (customer.contactID = address.contactID) INNER JOIN phone on (phone.contactID = customer.contactID)
    INNER JOIN email on (email.contactID = customer.contactID) INNER JOIN credentials on (credentials.customerID = customer.customerID)";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $customerID = $row['customerID'];
            $user_username = $row['username'];
            $user_password = $row['password'];
            $email = $row['emailValue'];
            $phone = $row['phoneValue'];
            $address = $row['addressValue'];
            $fname = $row['first'];
            $lname = $row['last'];
        }
    }
	$customerID = 0;
	$sql = "SELECT * FROM loggedIn";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			$customerID = $row['customerID'];
		}
	}
	if($customerID < 3000 and $customerID > 1999)
	{
		$sql = "SELECT * FROM loggedIn INNER JOIN customer ON loggedIn.customerID = customer.customerID";
		$result = $conn->query($sql);
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
		$result = $conn->query($sql);
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
					<li><a id = "loginLink" href="log_in.php"><?php echo $customerName;?></a></li>
					<li><a href="index.php">Home</a></li>
					<li><a href="search.php?series=&title=&author=">Search</a></li>
					<li><a href="browse.php?genre=&arrange=">Browse</a></li>
					<li><a href="cart.php">Cart</a></li>
				</ul>
			</div>
		</header>
		
        <div style="margin-top: 100px;
		margin-bottom: 10px;
		text-align: center;
		background: #E2BE9E;
		margin-left: 30;
		margin-right: auto;
		width: 95%;
		height: 83%;
		border-style: dashed;
		border-radius: 7px;
		border-color: #7C1313;
		display:inline-block;">
		
		<h5><b>First Name: </b></h5>
		<h6 id = "fname" contenteditable = "true">first</h6>
		<h5><b>Last Name: </b></h5>
		<h6 id = "lname"  contenteditable = "true">last</h6>
		<h5><b>Adress: </b></h5>
		<h6 id = "address" contenteditable = "true">address</h6>
		<h5><b>Phone: </b></h5>
		<h6 id = "phone" contenteditable = "true">phone</h6>
		<h5><b>Email: </b></h5>
		<h6 id = "email" contenteditable = "true">email</h6>
		<h5><b>Username: </b></h5>
		<h6 id = "username" contenteditable = "true">username</h6>
		<h5><b>Password: </b></h5>
		<h6 id = "password" contenteditable = "true">password</h6>
		<button id="checkoutButton" onclick="updateInfo()" style = "margin-top: -15px; margin-left:80%;
		background: #7C1313; border: 1px solid #7C1313; border-style: outset; color: #E2BE9E; border-radius: 7px;">Update User Account</button>
	</div>

	

	</body>

</html>

<script>
	initValues();
	function initValues()
	{
		var first = "<?php echo $fname;?>";
		var last = "<?php echo $lname;?>";
		var address = "<?php echo $address;?>";
		var email = "<?php echo $email?>";
		var phone = "<?php echo $phone?>";
		var username = "<?php echo $user_username?>";
		var password = "<?php echo $user_password?>";
		document.getElementById("fname").innerHTML = first;
		document.getElementById("lname").innerHTML = last;
		document.getElementById("address").innerHTML = address;
		document.getElementById("email").innerHTML = email;
		document.getElementById("phone").innerHTML = phone;
		document.getElementById("username").innerHTML = username;
		document.getElementById("password").innerHTML = password;
	}
	function updateInfo()
	{
		var first = document.getElementById("fname").innerHTML;
		var last = document.getElementById("lname").innerHTML;
		var address = document.getElementById("address").innerHTML;
		var email = document.getElementById("email").innerHTML;
		var phone = document.getElementById("phone").innerHTML;
		var username = document.getElementById("username").innerHTML;
		var password = document.getElementById("password").innerHTML;
		
		$.ajax({
			type: "POST",
			url: "update_user_account.php",
			data: "first="+first+"&last="+last+"&address="+address+"&email="+email+"&phone="+phone+"&username="+username+"&password="+password,
			success: function(data)
			{
				location.reload();
			}
			});
	}
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