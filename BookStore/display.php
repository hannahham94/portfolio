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

//variable to track what to modify
$modify = "contactDetails";

$orders = array();
$orderItems = array();
$authors = array();
$books = array();
$customers = array();
$suppliers = array();
$supplierReps = array();
$organizations = array();

$table = null;
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
		<form name = "display" method = "post">
			<select name="dbSelect" id = "which_table"
						style= "width: 200;
								background: #E2BE9E;
								border: 2px solid #7C1313;
								border-radius: 7px;
								margin-top: 15px;
								margin-right: 7px;">
							<option value = ""></option>
							<option value ="Book">Books</option>
							<option value="Author">Authors</option>
							<option value ="Order">Orders</option>
							<option value ="Customer">Customers</option>
							<option value ="Publisher">Publishers</option>
			</select>
			<input type = "submit" name = "display" value="Display" 
			style="background: #7C1313; border: 1px solid #7C1313; border-style: outset; color: #E2BE9E; border-radius: 7px; margin-left: 10px;">
		</div>
		
		<?php  
			if(isset($_POST['display'])) {
				$table = $_POST['dbSelect'];
			}
		?>
		
		<div style="margin-top: 25;
				text-align: center;
				background: #E2BE9E;
				margin-left: 30;
				margin-right: auto;
				width: 95%;
				height: 430px;
				border-style: dashed;
				border-radius: 7px;
				border-color: #7C1313;
				display:inline-block;">
				
				<p style="text-align: center;"><b>Records for <?php echo $table ?></b></p>
				
				<table class = "table" id = "displayTable" style="display:inline-block; margin-right: 100px; margin-left: 30px;">
					
		
		<?php
			if(isset($_POST['display'])) {
						if(isset($_POST['dbSelect'])) {
							$table = $_POST['dbSelect'];
							if($table == "Book") {
								$ints = 0;
								$sql = "SELECT * FROM book INNER JOIN assigned ON assigned.ISBN = book.ISBN
														   INNER JOIN category ON assigned.categoryCode = category.categoryCode";
								if($result = mysqli_query($link, $sql)) {
									if(mysqli_num_rows($result) > 0) {
										while($row = mysqli_fetch_array($result)) {
											$books[$ints] = "<br>" . "Title: " . $row['title'] . "<br>" . "Review: " . $row['userReview'] .
											"<br>" . "Price: " . $row['price'] . "<br>" . "ISBN: " . $row['ISBN'] .
											"<br>" . "Supplier: " . $row['sName'] . "<br>" . "Pub Date: " . $row['pubDate'] . "<br>" . 
											"Genre: " . $row['categoryDescription'] . "<br><br>";
											$ints += 1;
										}
									}
								}
								else
									echo "ERROR: Could not execute $sql" . mysqli_error($link);
								echo '<tr>';
								for($i = 0; $i < count($books); ++$i) {
									echo '<td>' . $books[$i] . '</td>';
									if($i % 2 != 0) {
										echo '</tr><tr>';
									}
								}
								echo '</tr>';
							}
							else if($table == "Author") {
								$ints = 0;
								$sql = "SELECT * FROM (((author INNER JOIN phone ON author.contactID=phone.contactID)
															 INNER JOIN email ON author.contactID=email.contactID)
															 INNER JOIN address ON author.contactID=address.contactID)";
								if($result = mysqli_query($link, $sql)) {
									if(mysqli_num_rows($result) > 0) {
										while($row = mysqli_fetch_array($result)) {
											$authors[$ints] = "<br />" . "Author: " . $row['first'] . " " . $row['last'] .
											"<br>" . "ID: " . $row['authorID'] . "<br>" . "DOB: " . $row['DoB'] .
											" Gender: " . $row['gender'] . "<br>" . "Phone: " . $row['phoneValue'] .
											"<br>" . "Email: " . $row['emailValue'] . "<br>" . "Address: " . $row['addressValue'] . "<br /><br />";
											$ints += 1;
										}
									}
								}
								echo '<tr>';
								for($i = 0; $i < count($authors); ++$i) {
									echo '<td>' . $authors[$i] . '</td>';
									if($i % 2 != 0) {
										echo '</tr><tr>';
									}
								}
								echo '</tr>';
							}
							else if($table == "Order") {
								$ints = 0;
								$sql = "SELECT * FROM orders INNER JOIN customer ON orders.customerId = customer.customerID";
								if($result = mysqli_query($link, $sql)) {
									if(mysqli_num_rows($result) > 0) {
										while($row = mysqli_fetch_array($result)) {
											$orderIDs[$ints] = $row['orderID'];
											$orders[$ints] = "<br>Order ID: " . $row['orderID'] . "<br>Customer ID: " . $row['customerId']
											. "<br />" . "Customer Name: " . $row['first'] . " " . $row['last']
											. "<br />" . "Date Placed: " . $row['orderDate'] . " Order Total: " . $row['orderValue'] . "<br />";
											$ints += 1;
											}
										}
									}//end if for getting orders
								
								for($i=0; $i<count($orders); ++$i) {
									$orderItems[$i] = "";
									$sql = "SELECT * FROM orderitem INNER JOIN book ON orderitem.ISBN = book.ISBN
																			WHERE orderID = '$orderIDs[$i]'";
									if($result = mysqli_query($link, $sql)) {
										if(mysqli_num_rows($result) > 0) {
											while($row = mysqli_fetch_array($result)) {
												$orderItems[$i] = $orderItems[$i] . "<br>ISBN: " . $row['ISBN'] . " Price: " . $row['itemPrice'] . "<br />"
												. "Book Title: " . $row['title'] . "<br />";
											}
										}
									}//end if for getting order items
								}//end for loop
								echo '<tr>';
								for($i = 0; $i < count($orders); ++$i) {
									echo '<td>' . $orders[$i] . $orderItems[$i] . '</td>';
									if($i % 2 != 0) {
										echo '</tr><tr>';
									}
								}
								echo '</tr>';
							}//end display orders
							else if($table == "Customer") {
								$ints = 0;
								$sql = "SELECT * FROM customer INNER JOIN email ON email.contactID = customer.contactID
															   INNER JOIN phone ON phone.contactID = customer.contactID
															   INNER JOIN address ON address.contactID = customer.contactID";
								if($result = mysqli_query($link, $sql)) {
									if(mysqli_num_rows($result) > 0) {
										while($row = mysqli_fetch_array($result)) {
											$customers[$ints] = "<br>Customer ID: " . $row['customerID'] . "<br>" . "Name: " . $row['first'] . " " . $row['last'] . "<br>" .
											"Phone Number: " . $row['phoneValue'] . "<br>Email: " . $row['emailValue'] . "<br>" . "Address: " . $row['addressValue'] . "<br><br>";
											$ints += 1;
										}
									}
								}
								echo '<tr>';
								for($i = 0; $i < count($customers); ++$i) {
									echo '<td>' . $customers[$i] . '</td>';
									if($i % 2 != 0) {
										echo '</tr><tr>';
									}
								}
								echo '</tr>';
							}
							else if($table == "Publisher") {
								$ints = 0;
								$sql = "SELECT * FROM supplier";
								if($result = mysqli_query($link, $sql)) {
									if(mysqli_num_rows($result) > 0) {
										while($row = mysqli_fetch_array($result)) {
											$supplierNames[$ints] = $row['Name'];
											$suppliers[$ints] = "<br>Publisher: " . $row['Name'] . "<br>";
											$ints += 1;
										}
									}
								}
								for($i = 0; $i<count($suppliers); ++$i) {
									$supplierReps[$i] = "";
									$sql = "SELECT * FROM supplierrep WHERE sName = '$supplierNames[$i]'";
									if($result = mysqli_query($link, $sql)) {
										if(mysqli_num_rows($result) > 0) {
											while($row = mysqli_fetch_array($result)) {
												$organizations[$i] = $row['organization'];
												$supplierReps[$i] = $supplierReps[$i] . "<br>ID: " . $row['ID'] . "<br>Name: " . $row['first'] . " " . $row['last'] .
												"<br>Email: " . $row['email'] . "<br>Cell Number: " . $row['cellNumber'] .
												"<br>Work Phone: " . $row['workNumber'] .
												"<br><br>";
											}
										}
										else {
											$organizations[$i] = "";
											$supplierReps[$i] = "No Representatives";
										}
									}
								}
								echo '<tr>';
								for($i = 0; $i < count($suppliers); ++$i) {
									echo '<td>' . $suppliers[$i] . "<br>Organization: " .
									$organizations[$i] . "<br><br>Representatives:" . $supplierReps[$i] .'</td>';
									if($i % 2 != 0) { 
										echo '</tr><tr>';
									}
								}
								echo '</tr>';
							
							}
						}
					}
		?>
		
		</table>
		</div>

		</form>
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

td{
	display:inline-block;
	margin-right: 50px; 
	margin-left: 50px;
	margin-top: 20px;
	width: 450px;
	border: 1px solid;
	padding-left: 15px;
}
table{
	margin-top: 5px;
	overflow-y:scroll;
	width: 95%;
	height: 350px;
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

</style>