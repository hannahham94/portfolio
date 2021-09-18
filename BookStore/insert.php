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

$rids = array();

$ints = 0;
$sql = "SELECT * FROM supplierrep";
if($result = mysqli_query($link, $sql)) {
	$count = mysqli_num_rows($result);
	while($row = mysqli_fetch_array($result)) {
		$rids[$ints] = $row['ID'];
		$ints += 1;
	}
}
else
	echo "FAILED" . mysqli_error($link);

$highRepID = $rids[count($rids)-1];

$authors = array();
$ints = 0;
//populates an author array to prevent adding authors twice
$sql = "SELECT * FROM author ORDER BY authorID";
if($result = mysqli_query($link, $sql)) {
	$count = mysqli_num_rows($result);
	while($row = mysqli_fetch_array($result)) {
		$authors[$ints] = $row['first'] . " " . $row['last'];
		$ids[$ints] = $row['contactID'];
		$ints += 1;
	}
}
else
	echo "FAILED" . mysqli_error($link);
//assign highest ID to a variable so it can be incremented when adding an author
$highID = $ids[count($ids)-1];


$ISBNs = array();
$ints = 0;
	
//populates an ISBN array to prevent adding books twice
$sql = "SELECT * FROM book";
if($result = mysqli_query($link, $sql)) {
	$count = mysqli_num_rows($result);
	while($row = mysqli_fetch_array($result)) {
		$ISBNs[$ints] = $row['ISBN'];
		$ints += 1;
	}
}
else
	echo "FAILED" . mysqli_error($link);

$ints = 0;
//populate suppliers array to prevent errors
$sql = "SELECT * FROM supplier";
if($result = mysqli_query($link, $sql)) {
	$count = mysqli_num_rows($result);
	while($row = mysqli_fetch_array($result)) {
		$suppliers[$ints] = $row['Name'];
		$ints += 1;
	}
}
else
	echo "FAILED" . mysqli_error($link);

$supplierReps = array();
$ints = 0;
//populate supplier reps array to prevent errors
$sql = "SELECT * FROM supplierrep";
if($result = mysqli_query($link, $sql)) {
	$count = mysqli_num_rows($result);
	while($row = mysqli_fetch_array($result)) {
		$supplierReps[$ints] = $row['first'] . " " . $row['last'];
		$ints += 1;
	}
}
else
	echo "FAILED" . mysqli_error($link);


$temp = false;

$title = "";
$authorFirst = "";
$authorLast = "";
$genre = "";

$categoryCode = 0;

$review = 0;
$price = "";
$series = "";
$ISBN = "";
$pubdate = "";
$sName = "";
$gender = "";
$dob = "";
$phone = "";
$email = "";
$address = "";
$organization = "";
$repFirst = "";
$repLast = "";
$repEmail = "";
$repCell = "";
$repWork = "";
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

		<main style="margin-top: 100px;
					text-align: center;
					background: #E2BE9E;
					margin-left: 30;
					margin-right: auto;
					width: 95%;
					height: 825px;
					border-style: dashed;
					border-radius: 7px;
					border-color: #7C1313;
					display:inline-block;">

			<form name = 'search' method = 'post'>
				<br />			
				<b>Book</b><br /><br />			
				Title: <input type="text" name = "titlebox" id = "titlebox" style="margin-right: 20px;">
				
				Review: <input type="text" name = "reviewbox" id= "reviewbox" style="margin-right: 20px;">
				 
				Price: <input type="text" name = "pricebox" id= "pricebox" style="margin-right: 20px;"><br/>
				<br/>
				
				ISBN: <input type="text" name = "isbnbox" id= "isbnbox" style="margin-right: 20px;">
				
				Series Name: <input type="text" name = "seriesbox" id= "seriesbox" style="margin-right: 20px;"><br/>
				<br/>
				
				Publication date: <br /><br />
				Month(2 digit): <input type="text" name = "pubmonthbox" id= "pubmonthbox" style="margin-right: 20px;">
				Day(2 digit): <input type="text" name = "pubdaybox" id= "pubdaybox" style="margin-right: 20px;">
				Year(4 digits): <input type="text" name = "pubyearbox" id= "pubyearbox" style="margin-right: 20px;"><br /><br />
				
				Genre: <select name = "genrebox" id = "genrebox"
							style= "width: 200;
									background: #E2BE9E;
									border: 2px solid #7C1313;
									border-radius: 7px;
									border-style:outset">
								<option value=""></option>
								<option value="Young Adult">Young Adult</option>
								<option value="Romance">Romance</option>
								<option value="Children">Children</option>
								<option value="Non-Fiction">Non-Fiction</option>
								<option value="Classic">Classic</option>
								<option value="Biography">Biography</option>
						</select>
				
				<br/>
				<br />			
				
				<b>Publisher</b><br /> <br />
				Publisher: <input type="text" name = "publisherbox" id= "publisherbox" style="margin-right: 20px;">
				Organization: <input type="text" name = "organizationbox" id= "organizationbox" style="margin-right: 20px;"><br />
				<br />
				Representative Information:
				<br />
				First: <input type="text" name = "repName1box" id= "repName1box" style="margin-right: 20px;">
				Last: <input type="text" name =  "repName2box" id= "repName2box" style="margin-right: 20px;"><br />
				<br />
				Email: <input type="text" name = "repemailbox" id= "repemailbox" style="margin-right: 20px;">
				Cell Phone: <input type="text" name = "cellbox" id= "cellbox" style="margin-right: 20px;">
				Work Phone: <input type="text" name = "workbox" id= "workbox" style="margin-right: 20px;"><br />
				<br />
				<b>Author</b><br /> <br />
				First: <input type="text" name = "author1box" id= "author1box" style="margin-right: 20px;">
				Last: <input type="text" name = "author2box" id= "author2box" style="margin-right: 20px;">
				Gender: <input type="text" name = "genderbox" id= "genderbox"><br /><br />
				DoB:<br / ><br />
				Month (2 digit): <input type="text" name = "monthbox" id= "monthbox" style="margin-right: 20px;">
				Day (2 digit): <input type="text" name = "daybox" id= "daybox" style="margin-right: 20px;">
				Year (4 digit): <input type="text" name = "yearbox" id= "yearbox">
				<br/>
				<br/>
				
				Phone: <input type="text" name = "phonebox" id= "phonebox" style="margin-right: 20px;">
				
				Email: <input type="text" name = "emailbox" id= "emailbox" style="margin-right: 20px;">
				
				Address: <input type="text" name = "addressbox" id= "addressbox"><br/>
				<br/>
				
				
				<input type = "submit" name = "submit" value="Add Information to Database" 
				style="background: #7C1313; border: 1px solid #7C1313; border-style: outset; color: #E2BE9E; border-radius: 7px; margin-top: 15px; margin-left: 10px;"><br/>
				<?php
				if((isset($_POST['submit'])) && !empty($_POST['isbnbox']))
				{
					$ISBN = $_POST['isbnbox'];
				}
				if((isset($_POST['submit'])) && !empty($_POST['reviewbox']))
				{
					$review = (int) $_POST['reviewbox'];
				} 
				if((isset($_POST['submit'])) && !empty($_POST['titlebox']))
				{
					$title = $_POST["titlebox"];
				} 
				if((isset($_POST['submit'])) && !empty($_POST['pricebox']))
				{
					$price = $_POST['pricebox'];
				} 
				if((isset($_POST['submit'])) && !empty($_POST['publisherbox']))
				{
					$sName = $_POST['publisherbox'];
				} 
				if((isset($_POST['submit'])) && !empty($_POST['pubyearbox'])
					&& !empty($_POST['pubdaybox']) && !empty($_POST['pubmonthbox']))
				{
					$pubyear = $_POST['pubyearbox'];
					$pubday = $_POST['pubdaybox'];
					$pubmonth = $_POST['pubmonthbox'];
					if(strlen($pubday) == 1) {
						$pubday = "0" . $pubday;
					}
					if(strlen($pubmonth) == 1) {
						$pubmonth = "0" . $pubmonth;
					}
					$pubdate = $pubmonth . "/" . $pubday . "/" . $pubyear;
				} 
				if((isset($_POST['submit'])) && !empty($_POST['author1box']))
				{
					$authorFirst = $_POST['author1box'];
				} 
				if((isset($_POST['submit'])) && !empty($_POST['author2box']))
				{
					$authorLast = $_POST['author2box'];
				} 
				if((isset($_POST['submit'])) && !empty($_POST['genrebox']))
				{
					$genre = $_POST['genrebox'];
				} 
				if((isset($_POST['submit'])) && !empty($_POST['genderbox']))
				{
					$gender = $_POST['genderbox'];
				} 
				if((isset($_POST['submit'])) && !empty($_POST['seriesbox']))
				{
					$series = $_POST['seriesbox'];
				} 
				if((isset($_POST['submit'])) && !empty($_POST['yearbox'])
				&& !empty($_POST['monthbox']) && !empty($_POST['daybox']))
				{
					$dob = $_POST['monthbox'] . "/" . $_POST['daybox'] . "/" . $_POST['yearbox'];
				} 
				if((isset($_POST['submit'])) && !empty($_POST['phonebox']))
				{
					$phone = $_POST['phonebox'];
				} 
				if((isset($_POST['submit'])) && !empty($_POST['emailbox']))
				{
					$email = $_POST['emailbox'];
				} 
				if((isset($_POST['submit'])) && !empty($_POST['addressbox']))
				{
					$address = $_POST['addressbox'];
				} 
				if((isset($_POST['submit'])) && !empty($_POST['organizationbox']))
				{
					$organization = $_POST['organizationbox'];
				} 
				if((isset($_POST['submit'])) && !empty($_POST['repName1box']))
				{
					$repFirst = $_POST['repName1box'];
				} 
				if((isset($_POST['submit'])) && !empty($_POST['repName2box']))
				{
					$repLast = $_POST['repName2box'];
				} 
				if((isset($_POST['submit'])) && !empty($_POST['repemailbox']))
				{
					$repEmail = $_POST['repemailbox'];
				} 
				if((isset($_POST['submit'])) && !empty($_POST['cellbox']))
				{
					$repCell = $_POST['cellbox'];
				} 
				if((isset($_POST['submit'])) && !empty($_POST['workbox']))
				{
					$repWork = $_POST['workbox'];
				} 
				?>
				
				<br />
				<br />
				
				<?php 
					if(isset($_POST['submit']))	{
						//check if ISBN exists
						for($i = 0; $i < count($ISBNs); ++$i) {
							if($ISBN == $ISBNs[$i])
								$temp = true;
						}
						
						if($temp == false && !empty($_POST['isbnbox'])) {
							$sql = "INSERT INTO book (ISBN, pubdate, price, title, userReview, sName) VALUES
								('$ISBN', '$pubdate', '$price', '$title', '$review', '$sName')";
								
							if(mysqli_query($link, $sql)){
							echo "Records for book added successfully. ";
							} else{
							echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
							}
						}
						else {
							echo "ISBN exists or not entered. ";
							$temp = false;
						}
						$temp = false;
						for($i = 0; $i < count($ISBNs); ++$i) {
							$sql = "SELECT * FROM series";
							if($result = mysqli_query($link, $sql)) {
								if(mysqli_num_rows($result) > 0) {
									while($row = mysqli_fetch_array($result)) {
										if($ISBN == $row['ISBN'])
											$temp = true;
									}
								}
							}
						}
						if($temp == false && !empty($_POST['seriesbox'])) {
							$sql = "INSERT INTO series VALUES
								('$ISBN', '$series')";
								
							if(mysqli_query($link, $sql)){
							echo "Records for series added successfully. ";
							} else{
							echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
							}
						}
						else {
							echo "Series exists or not entered.";
						}
						
						//check if the author exists
						$authorName = $authorFirst . " " . $authorLast;
						for($i = 0; $i < count($authors); ++$i) {
							if($authorName == $authors[$i])
								$temp = true;
						}
						
						if($temp == false && !empty($_POST['author1box']) && !empty($_POST['author2box'])) {
							//create author
							$id = $highID + 1;
							$sql = "INSERT INTO author (first, last, gender, DoB, authorID, contactID, sAuthorID) VALUES ('$authorFirst', '$authorLast', '$gender', '$dob' , '$id', '$id', '$id')";
							
							if(mysqli_query($link, $sql)){
								echo "Records for author added successfully. ";
							} else{
								echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
							}
							
							$sql = "INSERT INTO writtenby VALUES ('$ISBN', '$id')";
							if(mysqli_query($link, $sql)){
								echo "Records for written by added successfully. ";
							} else{
								echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
							}
							
							$sql = "INSERT INTO contactdetails VALUES ('$id')";
							if(mysqli_query($link, $sql)){
								echo "Records for author contact details added successfully. ";
							} else{
								echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
							}
							
							$sql = "INSERT INTO phone VALUES ('$id', '$phone')";
							if(mysqli_query($link, $sql)){
								echo "Records for author phone number added successfully. ";
							} else{
								echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
							}
							
							$sql = "INSERT INTO email VALUES ('$id', '$email')";
							if(mysqli_query($link, $sql)){
								echo "Records for author email added successfully. ";
							} else{
								echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
							}
							
							$sql = "INSERT INTO address VALUES ('$id', '$address')";
							if(mysqli_query($link, $sql)){
								echo "Records for author address added successfully. ";
							} else{
								echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
							}
						}
						else {
							echo "Author exists or name not entered. ";
							$temp = false;
							
							$sql = "SELECT * FROM writtenby";
							if($result = mysqli_query($link, $sql)) {
								if(mysqli_num_rows($result) > 0) {
									while($row = mysqli_fetch_array($result)) {
										if($ISBN == $row['ISBN'])
											$temp = true;
									}
								}
							}
							
							if($temp == false && $ISBN != "") {
								//get author
								$sql = "SELECT authorID FROM author WHERE first = '$authorFirst' AND last = '$authorLast'";
								if($result = mysqli_query($link, $sql)){
									if(mysqli_num_rows($result) > 0) {
										while($row = mysqli_fetch_array($result)) {
											$id = $row['authorID'];
										}
									}
								} else{
									echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
								}
								
								//insert written by values
								$sql = "INSERT INTO writtenby VALUES ('$ISBN', '$id')";
								
								if(mysqli_query($link, $sql)){
									echo "Records for written by added successfully. ";
								} else{
									echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
								}
							}
							else {
								echo "Record exists for written by or not entered. ";
								$temp = false;
							}
						}
						
						
						$temp = false;
						
						$sql = "SELECT * FROM assigned";
						if($result = mysqli_query($link, $sql)) {
							if(mysqli_num_rows($result) > 0) {
								while($row = mysqli_fetch_array($result)) {
									if($ISBN == $row['ISBN'])
										$temp = true;
								}
							}
						}
						
						//insert genre
						if($temp == false && !empty($_POST['genrebox']) && $genre != "") {
							$sql = "SELECT * FROM category WHERE categoryDescription = '$genre'";
								
								if($result = mysqli_query($link, $sql)){
									if(mysqli_num_rows($result) > 0) {
										while($row = mysqli_fetch_array($result)) {
											$categoryCode = $row['categoryCode'];
										}
									}
								} else{
									echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
								}
							
							$sql = "INSERT INTO assigned VALUES ('$ISBN', '$categoryCode')";
								
								if(mysqli_query($link, $sql)){
									echo "Records for assigned genre added successfully. ";
								} else{
									echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
								}
						}
						else {
							echo "Genre already assigned or not entered.";
						}
						
						$temp = false;
						
						for($i=0; $i < count($suppliers); ++$i) {
							if($sName == $suppliers[$i]) {
								$temp = true;
							}
						}
						
						if($temp == false && !empty($_POST['publisherbox'])) {
							$sql = "INSERT INTO supplier VALUES ('$sName')";
							if(mysqli_query($link, $sql)){
									echo "Records for assigned genre added successfully. ";
								} else{
									echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
								}
						}
						else {
							echo "Supplier already exists or was not entered. ";
						}
						$temp = false;
						$repName = $repFirst . " " . $repLast;
						for($i=0; $i < count($suppliers); ++$i) {
							if($repName == $supplierReps[$i]) {
								$temp = true;
							}
						}
						if($temp == false && !empty($_POST['repName1box']) && !empty($_POST['repName2box'])) {
							$repID = $highRepID + 1;
							
							$sql = "INSERT INTO supplierrep VALUES ('$repFirst', '$repLast', '$repID', '$organization', '$repEmail', '$repCell', '$repWork', '$sName')";
							if(mysqli_query($link, $sql)){
								echo "Records for supplier rep added successfully. ";
							} else{
								echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
							}
						}
						else {
							echo "Supplier rep already exists or was not entered. ";
						}
						
					}
				?>
	
		
			</form>
		</main>
		<br />
		<br />

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