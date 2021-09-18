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


$genre = htmlspecialchars($_GET["genre"]);
$orderBy = htmlspecialchars($_GET["arrange"]);
$titles = array();
$authors = array();
$authorsTemp = array();
$genres = array();
$prices = array();
$ISBNs = array();
$pubDates = array();
$authorIDs = array();
$categoryCodes = array();
$ints = 0;
$authorID;
$categoryCode;
$reviews = array();
$seriesNames = array();
if($orderBy != null && $genre == null) {
	if($orderBy != "author-alphabet" && $orderBy != "series-alphabet") {
		if($orderBy == "pricelow") {
			$sql = "SELECT * FROM book ORDER BY price";
			if($result = mysqli_query($link, $sql)) {
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result)) {
						$titles[$ints] = $row['title'];
						$prices[$ints] = $row['price'];
						$reviews[$ints] = $row['userReview'];
						$pubDates[$ints] = $row['pubDate'];
						$ISBNs[$ints] = $row['ISBN'];
						$ints += 1;
					}
				}
			}
		}//end if for order by price low to high
		else if($orderBy == "pricehigh") {
			$sql = "SELECT * FROM book ORDER BY price DESC";
			if($result = mysqli_query($link, $sql)) {
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result)) {
						$titles[$ints] = $row['title'];
						$prices[$ints] = $row['price'];
						$reviews[$ints] = $row['userReview'];
						$pubDates[$ints] = $row['pubDate'];
						$ISBNs[$ints] = $row['ISBN'];
						$ints += 1;
					}
				}
			}
		}//end if price high to low
		
		else if($orderBy == "published") {
			$sql = "SELECT * FROM book ORDER BY SUBSTRING(pubDate, 7, 10) DESC";
			if($result = mysqli_query($link, $sql)) {
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result)) {
						$titles[$ints] = $row['title'];
						$prices[$ints] = $row['price'];
						$reviews[$ints] = $row['userReview'];
						$pubDates[$ints] = $row['pubDate'];
						$ISBNs[$ints] = $row['ISBN'];
						$ints += 1;
					}
				}
			}
		}//end if price high to low
		
		else if($orderBy == "title-alphabet") {
			$sql = "SELECT * FROM book ORDER BY title";
				if($result = mysqli_query($link, $sql)) {
					if(mysqli_num_rows($result) > 0) {
						while($row = mysqli_fetch_array($result)) {
							$titles[$ints] = $row['title'];
							$prices[$ints] = $row['price'];
							$reviews[$ints] = $row['userReview'];
							$pubDates[$ints] = $row['pubDate'];
							$ISBNs[$ints] = $row['ISBN'];
							$ints += 1;
						}
					}
				}
		}//end if ordered by title
		
		else if($orderBy == "review") {
			$sql = "SELECT * FROM book ORDER BY userReview DESC";
			if($result = mysqli_query($link, $sql)) {
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result)) {
						$titles[$ints] = $row['title'];
						$prices[$ints] = $row['price'];
						$reviews[$ints] = $row['userReview'];
						$pubDates[$ints] = $row['pubDate'];
						$ISBNs[$ints] = $row['ISBN'];
						$ints += 1;
					}
				}
			}
		}//end if ordered by review
		
		for($j = 0; $j < count($ISBNs); ++$j) {
			$sql = "SELECT * FROM writtenby INNER JOIN author ON author.authorID = writtenby.authorID
										    WHERE ISBN = '$ISBNs[$j]'";
				if($result = mysqli_query($link, $sql)) {
					if(mysqli_num_rows($result) > 0) {
						while($row = mysqli_fetch_array($result)) {
							$authorIDs[$j] = $row['authorID'];
							$authors[$j] = $row['first'] . " " . $row['last'];
						}
					}
				}
				
		}//end for loop to populate authors
		
	}//end if ordering by book
	else if($orderBy == "author-alphabet") {
		$ints = 0;
		$sql = "SELECT * FROM author ORDER BY last";
			if($result = mysqli_query($link, $sql)) {
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result)) {
						$authorsTemp[$ints] = $row['first'] . " " . $row['last'];
						$authorIDs[$ints] = $row['authorID'];
						$ints += 1;
					}
				}
			}
			
		$ints = 0;
		for($i = 0; $i < count($authorsTemp); ++$i) {
			//get author ID for author $i in the array
			$sql = "SELECT * FROM writtenby WHERE authorID = '$authorIDs[$i]'";
			if($result = mysqli_query($link, $sql)) {
					if(mysqli_num_rows($result) > 0) {
						while($row = mysqli_fetch_array($result)) {
							//store all ISBNs for the author
							$ISBNs[$ints] = $row['ISBN'];
							//store the current author into authors array
							$authors[$ints] = $authorsTemp[$i];
							$ints += 1;
						}
					}
			}
		}
			
		//get book information from ISBNs
		for($j = 0; $j < count($ISBNs); ++$j) {
			$sql = "SELECT * FROM book WHERE ISBN = '$ISBNs[$j]'";
				if($result = mysqli_query($link, $sql)) {
					if(mysqli_num_rows($result) > 0) {
						while($row = mysqli_fetch_array($result)) {
							$titles[$j] = $row['title'];
							$prices[$j] = $row['price'];
							$reviews[$j] = $row['userReview'];
							$pubDates[$j] = $row['pubDate'];
							$ISBNs[$j] = $row['ISBN'];
							$ints += 1;
						}
					}
				}
		}
	}//end if ordering by author
	else if($orderBy == "series-alphabet") {
		$ints = 0;
		$sql = "SELECT * FROM series INNER JOIN book ON book.ISBN = series.ISBN ORDER BY seriesName";
		if($result = mysqli_query($link, $sql)) {
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result)) {
						$ISBNs[$ints] = $row['ISBN'];
						$seriesNames[$ints] = $row['seriesName'];
						$prices[$ints] = $row['price'];
						$reviews[$ints] = $row['userReview'];
						$pubDates[$ints] = $row['pubDate'];
						$titles[$ints] = $row['title'];
						$ints += 1;
				}
			}
		}
		
		for($i = 0; $i < count($ISBNs); ++$i) {
			$sql = "SELECT * FROM author INNER JOIN writtenby ON writtenby.authorID = author.authorID
										 WHERE writtenby.ISBN = '$ISBNs[$i]'";
			if($result = mysqli_query($link, $sql)) {
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result)) {
						$authors[$i] = $row['first'] . " " . $row['last'];
					}
				}
			}
		}
	}//end order by series
	
	for($i = 0; $i < count($ISBNs); ++$i) {
		$sql = "SELECT * FROM assigned INNER JOIN category ON category.categoryCode = assigned.categoryCode
									   WHERE ISBN = '$ISBNs[$i]'";
			if($result = mysqli_query($link, $sql)) {
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result)) {
						$genres[$i] = $row['categoryDescription'];
					}
				}
			}
			
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
	}//end populate series and genres
	
}//end if order by is selected but not genre
$inst = 0;
if($genre != null && $orderBy == null) {
	$ints = 0;
	$sql = "SELECT * FROM book INNER JOIN assigned ON assigned.ISBN = book.ISBN
							   INNER JOIN category ON category.categoryCode = assigned.categoryCode
							   INNER JOIN writtenby ON writtenby.ISBN = book.ISBN
							   INNER JOIN author ON author.authorID = writtenby.authorID
							   WHERE category.categoryDescription = '$genre'";
	if($result = mysqli_query($link, $sql)) {
			if(mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_array($result)) {
					$ISBNs[$ints] = $row['ISBN'];
					$authors[$ints] = $row['first'] . " " . $row['last'];
					$titles[$ints] = $row['title'];
					$reviews[$ints] = $row['userReview'];
					$prices[$ints] = $row['price'];
					$genres[$ints] = $row['categoryDescription'];
					$pubDates[$ints] = $row['pubDate'];
					$ints += 1;
				}
			}
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
	
}
if($orderBy != null && $genre != null) {
	if($orderBy != "author-alphabet" && $orderBy != "series-alphabet") {
		if($orderBy == "pricehigh") {
			$ints = 0;
			$sql= "SELECT * FROM book INNER JOIN assigned ON book.ISBN = assigned.ISBN 
				   WHERE categoryCode = (SELECT categoryCode FROM category WHERE categoryDescription = '$genre') ORDER BY price DESC";
			if($result = mysqli_query($link, $sql)) {
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result)) {
						$titles[$ints] = $row['title'];
						$prices[$ints] = $row['price'];
						$reviews[$ints] = $row['userReview'];
						$pubDates[$ints] = $row['pubDate'];
						$ISBNs[$ints] = $row['ISBN'];
						$ints += 1;
					}
				}
			}
		}
		else if($orderBy == "pricelow") {
			$ints = 0;
			$sql= "SELECT * FROM book INNER JOIN assigned ON book.ISBN = assigned.ISBN 
				   WHERE categoryCode = (SELECT categoryCode FROM category WHERE categoryDescription = '$genre') ORDER BY price";
			if($result = mysqli_query($link, $sql)) {
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result)) {
						$titles[$ints] = $row['title'];
						$prices[$ints] = $row['price'];
						$reviews[$ints] = $row['userReview'];
						$pubDates[$ints] = $row['pubDate'];
						$ISBNs[$ints] = $row['ISBN'];
						$ints += 1;
					}
				}
			}
		}
		else if($orderBy == "title-alphabet") {
			$ints = 0;
			$sql= "SELECT * FROM book INNER JOIN assigned ON book.ISBN = assigned.ISBN 
				   WHERE categoryCode = (SELECT categoryCode FROM category WHERE categoryDescription = '$genre') ORDER BY title";
			if($result = mysqli_query($link, $sql)) {
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result)) {
						$titles[$ints] = $row['title'];
						$prices[$ints] = $row['price'];
						$reviews[$ints] = $row['userReview'];
						$pubDates[$ints] = $row['pubDate'];
						$ISBNs[$ints] = $row['ISBN'];
						$ints += 1;
					}
				}
			}
		}
		else if($orderBy == "review") {
			$ints = 0;
			$sql= "SELECT * FROM book INNER JOIN assigned ON book.ISBN = assigned.ISBN 
				   WHERE categoryCode = (SELECT categoryCode FROM category WHERE categoryDescription = '$genre') ORDER BY userReview DESC";
			if($result = mysqli_query($link, $sql)) {
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result)) {
						$titles[$ints] = $row['title'];
						$prices[$ints] = $row['price'];
						$reviews[$ints] = $row['userReview'];
						$pubDates[$ints] = $row['pubDate'];
						$ISBNs[$ints] = $row['ISBN'];
						$ints += 1;
					}
				}
			}
		}
		else if($orderBy =="published") {
			$ints = 0;
			$sql= "SELECT * FROM book INNER JOIN assigned ON book.ISBN = assigned.ISBN 
				   WHERE categoryCode = (SELECT categoryCode FROM category WHERE categoryDescription = '$genre') ORDER BY SUBSTRING(pubDate, 7, 10) DESC";
			if($result = mysqli_query($link, $sql)) {
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result)) {
						$titles[$ints] = $row['title'];
						$prices[$ints] = $row['price'];
						$reviews[$ints] = $row['userReview'];
						$pubDates[$ints] = $row['pubDate'];
						$ISBNs[$ints] = $row['ISBN'];
						$ints += 1;
					}
				}
			}
		}
		
		for($i = 0; $i < count($ISBNs); ++$i) {
			$sql = "SELECT * FROM assigned INNER JOIN category ON category.categoryCode = assigned.categoryCode
										WHERE ISBN = '$ISBNs[$i]'";
			if($result = mysqli_query($link, $sql)) {
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result)) {
						$genres[$i] = $row['categoryDescription'];
					}
				}
			}
		
			$sql = "SELECT * FROM writtenby INNER JOIN author ON author.authorID = writtenby.authorID
											WHERE ISBN = '$ISBNs[$i]'";
				if($result = mysqli_query($link, $sql)) {
					if(mysqli_num_rows($result) > 0) {
						while($row = mysqli_fetch_array($result)) {
							$authors[$i] = $row['first'] . " " . $row['last'];
						}
					}
				}
				
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
		}//end for loop to populate authors, series, and genres
	}
	else {
		if($orderBy == "series-alphabet") {
			$ints = 0;
			$sql = "SELECT * FROM book INNER JOIN series ON series.ISBN = book.ISBN 
									   INNER JOIN writtenby ON writtenby.ISBN = book.ISBN
									   INNER JOIN author ON writtenby.authorID = author.authorID
									   INNER JOIN assigned ON assigned.ISBN = book.ISBN
									   INNER JOIN category ON assigned.categoryCode = category.categoryCode
									   WHERE category.categoryDescription = '$genre'
									   ORDER BY series.seriesName";
			if($result = mysqli_query($link, $sql)) {
						if(mysqli_num_rows($result) > 0) {
							while($row = mysqli_fetch_array($result)) {
								$ISBNs[$ints] = $row['ISBN'];
								$authors[$ints] = $row['first'] . " " . $row['last'];
								$titles[$ints] = $row['title'];
								$reviews[$ints] = $row['userReview'];
								$prices[$ints] = $row['price'];
								$genres[$ints] = $row['categoryDescription'];
								$pubDates[$ints] = $row['pubDate'];
								$seriesNames[$ints] = $row['seriesName'];
								$ints += 1;
							}
						}
					}
		}//end sort by series
		else if($orderBy == "author-alphabet") {
			$ints = 0;
			$sql = "SELECT * FROM author INNER JOIN writtenby ON writtenby.authorID = author.authorID
										 INNER JOIN book ON writtenby.ISBN = book.ISBN
										 INNER JOIN assigned ON assigned.ISBN = book.ISBN
										 INNER JOIN category ON assigned.categoryCode = category.categoryCode
										 WHERE category.categoryDescription = '$genre'
										 ORDER BY author.last";
			if($result = mysqli_query($link, $sql)) {
						if(mysqli_num_rows($result) > 0) {
							while($row = mysqli_fetch_array($result)) {
								$authors[$ints] = $row['first'] . " " . $row['last'];
								$titles[$ints] = $row['title'];
								$reviews[$ints] = $row['userReview'];
								$prices[$ints] = $row['price'];
								$genres[$ints] = $row['categoryDescription'];
								$pubDates[$ints] = $row['pubDate'];
								$ISBNs[$ints] = $row['ISBN'];
								$ints += 1;
							}
						}
					}
		}//end sort by author
		
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
	}
}//end if $orderBy and $genre not null
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
				
				<form name = "order" method = "post">
				
				<p style= "display: inline-block; margin-left: 550px;">Browse Genre: </p>
				<select name="genreSelect" id = "genrebox"
				style= "width: 200;
					    background: #E2BE9E;
						border: 2px solid #7C1313;
						border-radius: 7px;">
					<option value =""</option>
				    <option value="Young Adult">Young Adult</option>
				    <option value="Romance">Romance</option>
					<option value="Children">Children</option>
					<option value="Non-Fiction">Non-Fiction</option>
					<option value="Classic">Classic</option>
					<option value="Biography">Biography</option>
				</select>
				
				<p style= "display: inline-block;">Order By: </p>
				<select name="orderSelect" id = "orderbox"
					style= "width: 200;
							background: #E2BE9E;
							border: 2px solid #7C1313;
							border-radius: 7px;
							margin-top: 15px;">
						<option value =""></option>
						<option value="pricelow">Price: Low to High</option>
						<option value="pricehigh">Price: High to Low</option>
						<option value="review">High Reviews</option>
						<option value="title-alphabet">Alphabetical by Title</option>
						<option value="author-alphabet">Alphabetical by Author</option>
						<option value="series-alphabet">Alphabetical by Series</option>
						<option value="published">Recently Published</option>
				</select>
					
				<button type="submit" id="browse" value="Browse" Onclick="setUrl(); return false;" 
				style = "background: #7C1313; border: 1px solid #7C1313; border-style: outset; color: #E2BE9E; border-radius: 7px; margin-left: 10px;">Browse</button>	
					
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
					<tr><th>Title</th> <th>Series</th> <th>Author</th> <th>Genre</th> <th>Price</th> <th>Published</th> <th>Review</th> <th>Add to Cart</th></tr>
				</table>
			</div>
		
		</main>
		
	</body>

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
	var pubDatesArray = <?php echo json_encode($pubDates); ?>;
	loadTable();
	function loadTable()
	{
		for(i = 0; i < titlesArray.length; i++)
		{
			var row = "<tr id=row" + i + "><td>" + titlesArray[i] + "</td><td>" + seriesArray[i] + "</td><td>" + authorsArray[i] + "</td><td>" 
			+ genresArray[i] + "</td><td>" + "$" + pricesArray[i] + "</td><td>" + pubDatesArray[i] + "</td><td>" + reviewsArray[i] + " / 5" + "</td>";
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
	
	function setUrl() {
		window.location.href = 'browse.php?genre=' + document.getElementById('genrebox').value + '&arrange=' + document.getElementById('orderbox').value;
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
input[type="checkbox"]{
  transform:scale(1.5, 1.5);
}
th{
	display:inline-block;
	margin-right: 20px; 
	margin-left: 10px;
	margin-top: 20px;
	border-bottom: 2px solid;
	width: 120px;
}
td{
	display:inline-block;
	text-align: center;
	margin-right: 20px; 
	margin-left: 10px;
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
</style>