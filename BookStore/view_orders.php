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
    $sql = "SELECT customerID FROM loggedIn";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $customerID = $row['customerID'];
        }
    }
    $ISBNs = array();
    $titles = array();
    $prices = array();
    $orderIds = array();
    $sql = "SELECT * FROM orderitem INNER JOIN book on (orderitem.ISBN = book.ISBN) INNER JOIN orders on (orders.orderID = orderitem.orderID) WHERE customerID = '$customerID'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            array_push($ISBNs, $row['ISBN']);
            array_push($titles, $row['title']);
            array_push($prices, $row['itemPrice']);
            array_push($orderIds, $row['orderID']);
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
					<li><a id = "loginLink" href="log_in.php"><?php echo $customerName;?></a></li>
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
		<table class = "table" id = "orderTable" style="display:inline-block; margin-right: 100px; margin-left: 10%; margin-top:30px;">
			<tr><th>Order Number</th><th>ISBN</th> <th>Title</th> <th>Price</th><th>Delete</th></tr>
		</table>
    </div>
    
	</body>

</html>

<script>
    var isbnAry =  <?php echo json_encode($ISBNs)?>;
    var titlesAry =  <?php echo json_encode($titles)?>;
    var priceAry =  <?php echo json_encode($prices)?>;
    var orderIdAry =  <?php echo json_encode($orderIds)?>;
    loadTable();
    function loadTable()
    {
        for(var i = 0; i < isbnAry.length; i++)
        {
            var row = "<tr><td>" + orderIdAry[i] + "</td><td>" + isbnAry[i] + "</td><td>" + titlesAry[i] + "</td><td>" + priceAry[i] + "</td>";
            row += '<td><div class="checkbox" onClick=remove("' + parseInt(i) + '")><input type="checkbox" class="check" id="customCheck' + i + '"></div></td></tr>';
            $('#orderTable').append(row);
        }
    }
    function remove(index)
    {
        $.ajax({
			type: "POST",
			url: "remove_order_item.php",
			data: "isbn=" + isbnAry[index] + "&orderID=" + orderIdAry[index],
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
#orderTable{
	display:inline-block;
	margin-right: 100px;
	margin-left: 10%;
	margin-top:30px;
	overflow-y:scroll;
	height: 90%;
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

input[type="checkbox"]{
  transform:scale(1.5, 1.5);
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