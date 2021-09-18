<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
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

$author = htmlspecialchars($_GET['author']);
$title = htmlspecialchars($_GET['title']);
$series = htmlspecialchars($_GET['series']);
$price = 0;
$titles = array();
$authors = array();
$genres = array();
$prices = array();
$ISBNs = array();
$authorIDs = array();
$reviews = array();
$authorID;
$categoryCodes = array();
$seriesNames = array();
$ints = 0;
if($title != null && $author == null && $series == null) {
	$sql = "SELECT * FROM book INNER JOIN writtenby ON writtenby.ISBN = book.ISBN
							   INNER JOIN author ON author.authorID = writtenby.authorID
							   INNER JOIN assigned ON assigned.ISBN = book.ISBN
							   INNER JOIN category ON category.categoryCode = assigned.categoryCode
							   WHERE book.title LIKE '%$title%'";
		if($result = mysqli_query($link, $sql)) {
			if(mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_array($result)) {
					$titles[$ints] = $row['title'];
					$prices[$ints] = $row['price'];
					$ISBNs[$ints] = $row['ISBN'];
					$authors[$ints] = $row['first'] . " " . $row['last'];
					$genres[$ints] = $row['categoryDescription'];
					$reviews[$ints] = $row['userReview'];
					$ints += 1;
				}
			}
		}
		else {
			$price = 1;
			echo mysqli_error($link);
		}
	
	for($i = 0; $i<count($ISBNs); ++$i) {
			$sql = "SELECT * FROM series WHERE ISBN = '$ISBNs[$i]'";
				if($result = mysqli_query($link, $sql)) {
						if(mysqli_num_rows($result) > 0) {
							while($row = mysqli_fetch_array($result)) {
								$seriesNames[$i] = $row['seriesName'];
							}
						}
						else {
							$seriesNames[$i] = "";
						}
					}
		}//end for to add series
	
	if($result)
		mysqli_free_result($result);
}//end if for title != 'n'
$ints = 0;
if($author != null && $title == null && $series == null) {
	//code to populate arrays for author search
	
	$sql = "SELECT * FROM author INNER JOIN writtenby ON writtenby.authorID = author.authorID
							   INNER JOIN book ON book.ISBN = writtenby.ISBN
							   INNER JOIN assigned ON assigned.ISBN = book.ISBN
							   INNER JOIN category ON category.categoryCode = assigned.categoryCode
							   WHERE CONCAT(author.first,' ', author.last) LIKE '%$author%'";
		if($result = mysqli_query($link, $sql)) {
			if(mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_array($result)) {
					$titles[$ints] = $row['title'];
					$prices[$ints] = $row['price'];
					$ISBNs[$ints] = $row['ISBN'];
					$authors[$ints] = $row['first'] . " " . $row['last'];
					$genres[$ints] = $row['categoryDescription'];
					$reviews[$ints] = $row['userReview'];
					$ints += 1;
				}
			}
		}
		else {
			$price = 1;
			echo mysqli_error($link);
		}
	
	for($i = 0; $i<count($ISBNs); ++$i) {
			$sql = "SELECT * FROM series WHERE ISBN = '$ISBNs[$i]'";
				if($result = mysqli_query($link, $sql)) {
						if(mysqli_num_rows($result) > 0) {
							while($row = mysqli_fetch_array($result)) {
								$seriesNames[$i] = $row['seriesName'];
							}
						}
						else {
							$seriesNames[$i] = "";
						}
					}
		}//end for to add series
	
	if($result)
		mysqli_free_result($result);
}
if($series != null && $title == null && $author == null) {
	$ints = 0;
	$sql = "SELECT * FROM series WHERE seriesName LIKE '%$series%'";		
	if($result = mysqli_query($link, $sql)) {
			if(mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_array($result)) {
					$ISBNs[$ints] = $row['ISBN'];
					$seriesNames[$ints] = $row['seriesName'];
					$ints += 1;
				}
			}
		}
		else {
			echo mysqli_error($link);
		}
	for($i = 0; $i<count($ISBNs); ++$i) {
		$sql = "SELECT * FROM book INNER JOIN writtenby ON writtenby.ISBN = book.ISBN
								   INNER JOIN author ON writtenby.authorID = author.authorID
								   WHERE book.ISBN = '$ISBNs[$i]'";		
		if($result = mysqli_query($link, $sql)) {
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result)) {
						$titles[$i] = $row['title'];
						$prices[$i] = $row['price'];
						$authors[$i] = $row['first'] . " " . $row['last'];
						$reviews[$i] = $row['userReview'];
					}
				}
			}
			else {
				echo mysqli_error($link);
			}
			
		$sql = "SELECT * FROM assigned INNER JOIN category ON category.categoryCode = assigned.categoryCode 
									   WHERE assigned.ISBN = '$ISBNs[$i]'";
		if($result = mysqli_query($link, $sql)) {
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result)) {
						$genres[$i] = $row['categoryDescription'];
					}
				}
			}
			else {
				echo mysqli_error($link);
			}
		
	}
}

?>

<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">-->
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
					
				<form name = 'search' method = 'post'>
				<p>
				Title: <input type="text" name = "titlebox" id = "titlebox" value = "" >
				</p>			
					
				<p>
				Author: <input type="text" name = "authorbox" id= "authorbox" value = "">
				</p>
					
				<p>
				Series: <input type="text" name = "seriesbox" id= "seriesbox" value = "">
				</p>
				
				
					
				<p style= 
				"border: 2px solid #7C1313; 
				border-style:outset;
				border-radius: 5px;
				width: 150;
				display: inline-block;
				margin: 10 10 10 100;
				background: #7C1313;" >
				
				
				<script type="text/javascript">
					function setUrl() {
						window.location.href = 'search.php?series=' + document.getElementById('seriesbox').value + '&title=' + document.getElementById('titlebox').value + '&author=' + document.getElementById('authorbox').value;
					};
				</script>
				
				<button type="submit" id="search" value="Search" Onclick="setUrl(); return false;" style = "color: #E2BE9E; background: #7C1313; border-style: none;">Search</button>
				
				
				</p>
			</form>
			</div>
			
			
			<div style="margin-top: 25;
				text-align: center;
				background: #E2BE9E;
				margin-left: 30;
				margin-right: auto;
				width: 95%;
				height: 415px;
				border-style: dashed;
				border-radius: 7px;
				border-color: #7C1313;
				display:inline-block;">
				
				<table class = "table" id = "searchTable" style="display:inline-block; margin-right: 100px; margin-left: 30px;">
					<tr><th>Title</th> <th>Series</th> <th>Author</th> <th>Genre</th> <th>Price</th> <th>Review</th> <th>Add to Cart</th></tr>
				</table>
			</div>
		
		</main>

</html>

<script>
	//fill search table
	var isbnsArray = <?php echo json_encode($ISBNs)?>;
	var titlesArray = <?php echo json_encode($titles); ?>;
	var authorsArray = <?php echo json_encode($authors); ?>;
	var genresArray = <?php echo json_encode($genres); ?>;
	var pricesArray = <?php echo json_encode($prices); ?>;
	var seriesArray = <?php echo json_encode($seriesNames); ?>;
	var reviewsArray = <?php echo json_encode($reviews); ?>;
	loadTable();
	function loadTable()
	{
		for(i = 0; i < titlesArray.length; i++)
		{
			var row = "<tr id=row" + i + "><td>" + titlesArray[i] + "</td><td>" + seriesArray[i] + "</td><td>" + authorsArray[i] + "</td><td>" 
			+ genresArray[i] + "</td><td>" + "$" + pricesArray[i] + "</td><td>" + reviewsArray[i] + " / 5" + "</td>";
			row += '<td><div class="checkbox" onClick=addToCart("' + parseInt(i) + '")><input type="checkbox" class="check" id="customCheck' + i + '"></div></td></tr>';
			$('#searchTable').append(row);
		}
	}
	function addToCart(index)
	{
		$.ajax({
			type: "POST",
			url: "add_to_shopping_cart.php",
			data: "isbn=" + isbnsArray[index],
			success: function(data)
			{
				$('#row'+index).remove();
				alert(titlesArray[index] + " added to cart.");
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
	document.getElementById('authorbox').oninput = function() {
		document.getElementById('titlebox').disabled = true;
		document.getElementById('seriesbox').disabled = true;
	};
	document.getElementById('titlebox').oninput = function() {
		document.getElementById('authorbox').disabled = true;
		document.getElementById('seriesbox').disabled = true;
	};
	document.getElementById('seriesbox').oninput = function() {
		document.getElementById('titlebox').disabled = true;
		document.getElementById('authorbox').disabled = true;
	};
</script>

<style type="text/css">
input[type="checkbox"]{
  transform:scale(1.5, 1.5);
}
th{
	display:inline-block;
	margin-right: 35px; 
	margin-left: 15px;
	margin-top: 20px;
	border-bottom: 2px solid;
	width: 120px;
}
td{
	display:inline-block;
	text-align: center;
	margin-right: 35px; 
	margin-left: 15px;
	margin-top: 20px;
	width: 120px;
}
table{
	margin-top: 5px;
	overflow-y:scroll;
	width: 95%;
	height: 400px;
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
main p {
	color: #7C1313;
	display: inline-block;
}
main input {
	background: #E2BE9E;
	border: 2px solid #7C1313;
	border-radius: 5px;
}
main p a:active {
	color: black;
}
</style>