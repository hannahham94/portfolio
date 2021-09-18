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
$sql = "SELECT book.ISBN, book.title, book.price From book INNER JOIN shoppingcart ON shoppingcart.ISBN=book.ISBN";
$result = $conn->query($sql);
$titles = array();
$isbns = array();
$prices = array();
if ($result->num_rows > 0) {
	// output data of each row
	while($row = $result->fetch_assoc()) {
		array_push($titles, $row['title']);
		array_push($isbns, $row['ISBN']);
		array_push($prices, $row['price']);
	}
} else {
	echo "0 results";
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
				<h1><a href="index.php">Books 'N Gerbils</a></h1>
				
				<ul>
					<li><a id = "loginLink" href="log_in.php"><?php echo $customerName;?></a></li>
					<li><a href="index.php">Home</a></li>
					<li><a href="search.php?series=&title=&author=">Search</a></li>
					<li><a href="browse.php?genre=&arrange=">Browse</a></li>
					<li><a href="cart.php">Cart</a></li>
				</ul>
			</div>
		</header>
	</body>

    <div style="margin-top: 150px;
		text-align: center;
		background: #E2BE9E;
		margin-left: 30;
		margin-right: auto;
		width: 95%;
		height: 300px;
		border-style: dashed;
		border-radius: 7px;
		border-color: #7C1313;
		display:inline-block;">
		<table class = "table" id = "cartTable" style="display:inline-block; margin-right: 100px; margin-left: -350px; margin-top:30px; height:250px;">
			<tr><th>ISBN</th> <th>Title</th> <th>Price</th></tr>
		</table>
	</div>

	<div style="margin-top: 50px;
		text-align: center;
		background: #E2BE9E;
		margin-left: 25%;
		margin-right: auto;
		margin-bottom: 10px;
		width: 50%;
		height: 200px;
		border-style: dashed;
		border-radius: 7px;
		border-color: #7C1313;
		display:inline-block;">
		<table class = "table" id = "priceTable" style="display:inline-block; margin-right: 100px;">
			
		</table>
		<button id="checkoutButton" onclick="checkout()">Checkout</button>
	</div>

</html>

<script>
	var isbns_ary = <?php echo json_encode($isbns)?>;
	var titles_ary = <?php echo json_encode($titles)?>;
	var price_ary = <?php echo json_encode($prices)?>;
	loadItemsTable();
	loadPriceTable();
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
	function checkout()
	{
		var customerName = "<?php echo $customerName?>";
		if(customerName !== "Login/Sign Up")
		{
			$.ajax({
			type: "POST",
			url: "checkout.php",
			data: "",
			success: function(data)
			{
				location.reload();
			}
			});
		}
		else
		{
			window.location = "log_in.php";
		}
	}
	function loadItemsTable()
	{
		for(var i = 0; i < isbns_ary.length; i++)
		{
			var row = "<tr><td>" + isbns_ary[i] + "</td><td>" + titles_ary[i] + "</td><td>$" + price_ary[i] + "</td><td id='remove' onClick=removeItemFromCart(" + i + ")>Remove</td></tr>";
			$('#cartTable').append(row);
		}
	}
	function loadPriceTable()
	{
		var subtotal = 0;
		for(var i  = 0; i < price_ary.length; i++)
		{
			subtotal = (parseFloat(subtotal) + parseFloat(price_ary[i])).toFixed(2);
		}
		var tax = (subtotal * .0825).toFixed(2);
		var total = (parseFloat(subtotal) + parseFloat(tax)).toFixed(2);
		var row = "<tr><td>Subtotal: </td><td>$" + subtotal + "</td></tr>";
		$('#priceTable').append(row);
		var row = "<tr><td>Tax: </td><td>$" + tax + "</td></tr>";
		$('#priceTable').append(row);
		var row = "<tr><td>Total: </td><td>$" + total + "</td></tr>";
		$('#priceTable').append(row);
	}
	async function removeItemFromCart(index)
	{
		await $.ajax({
			type: "POST",
			url: "remove_from_shopping_cart.php",
			data: "isbn=" + isbns_ary[index]
			});
		location.reload();
	}
</script>

<style type="text/css">
#remove:hover{
	cursor:pointer;
}
#checkoutButton{
	border-radius: 5px;
	color: #E2BE9E;
	border-color: #7C1313;
	background: #7C1313;
}
#checkoutButton, hover{
	cursor:pointer;
}
#cartTable{
	overflow-y:scroll;
}
th{
    display:inline-block;
	margin-right: 100px;
	width: 80px;
}
td{
	margin-top: 25px;
    display:inline-block;
	margin-right: 100px;
	width: 85px;
}
table::-webkit-scrollbar {
	width: 17px;
}
table::-webkit-scrollbar-track {
  background: #E2BE9E;
  -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
  border: 1px solid #7C1313;
}
table::-webkit-scrollbar-button {
	background: #7C1313;
	border-radius: 2px;
	border: 2px outset #7C1313;
}
table::-webkit-scrollbar-thumb {
	background: #7C1313;
	border: 2px outset #7C1313;
	border-radius: 5px;
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