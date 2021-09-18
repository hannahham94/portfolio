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
		
		<main>
			<div style = "text-align: center; margin-top: 100;">
				<img src = "books.jpg" alt = "Books" style="width: 95%; height: 430px; display:block ; margin-left: auto; margin-right: auto; border:5px solid #7C1313;">
			</div>
			
			<ul style = "
				text-align: center;
				background: #E2BE9E;
				margin-left: auto;
				margin-right: auto;
				width: 95%;
				height: 50px;
				border-style: dashed;
				border-radius: 7px;
				border-color: #7C1313;
				border-left-style: solid;
				border-right-style: solid;
				border-width:3;
			">
				<li><a href= "browse.php?genre=Young%20Adult&arrange=">Young Adult</a></li>
				<li><a href= "browse.php?genre=Classic&arrange=">Classic</a></li>
				<li><a href= "browse.php?genre=Romance&arrange=">Romance</a></li>
				<li><a href= "browse.php?genre=Children&arrange=">Children</a></li>
				<li><a href= "browse.php?genre=Non-Fiction&arrange=">Non-Fiction</a></li>

			</ul>
			
			<div style = 
				"text-align: center;"
			>
				<article>
					<p style="text-align: center;"><b>Noteworthy Authors</b></p>
					<p><a href= "search.php?series=&title=&author=Mortimer%20Smith">Mortimer "Morty" Smith Jr.</a></p>
					<p>Mortimer Smith is half-Gazorpian, which led to a difficult life being torn between the need for violence and
					   the human dilemma of right vs. wrong. Mortimer fought this struggle with finesse and proceeded to write the best
					   selling novel My Horrible Father, which is a highly recommended read.</p>
					<p><a href= "search.php?series=&title=&author=Kiera%20Cass">Kiera Cass</a></p>
					<p>Kiera Cass is a fantastic young adult author who is best known for writing the The Selection series, which has 5 main novels and multiple
					novellas as well.</p>
					<p><a href= "search.php?series=&title=&author=Scott%20Westerfeld">Scott Westerfeld</a></p>
					<p>Scott Westerfeld is another well-known young adult author. He is most known for the Uglies trilogy, though he has many
					 noteworthy series, such as Leviathon.</p>
				</article>
				<article>
					<p style="text-align: center;"><b>Best Sellers</b></p>
					<p><a href= "search.php?series=&title=My%20Horrible%20Father&author="><u>My Horrible Father</u></a> is an auto-biographical novel detailing
					      Mortimer "Morty" Smith Jr's early life with his father, Morty Smith. This includes being locked in a house for most of his
						  young life, dancing, and threats of poison gas. Oh my!</p>
					<p><a href= "search.php?series=&title=Workplace%20Mannerisms:%20A%20Manual&author="><u>Workplace Mannerisms: A Manual</u></a>
					    is a best-selling self-help book written by sergeant Amy Santiago of the NYPD.
					   This 10,000 page manual describes in detail how one should behave in the workplace, including tips for organization
					   of files. This manual comes in a neat binder with tabs, which is a fine example of these organization tips in practice.</p>
					<p><a href= "search.php?series=&title=The%20Book%20of%20Moe&author="><u>The Book of Moe</u></a>, written and illustrated by Matt Groening, is a book about the beloved
					     characer from The Simpsons, Moammar "Moe" Syzlack, a bartender at Moe's Tavern in Springfield. Sections of this book include: Staring Into
						  the Void, Memories of Moe, and Poetry in Moe-tion.</p>
				</article>
			</div>
			
			<div align="right"
			style = "margin-top: 20;
				margin-bottom: 10;
				text-align: center;
				background: #E2BE9E;
				margin-left: 30;
				margin-right: auto;
				width: 95%;
				height: 60px;
				border-style: dashed;
				border-radius: 7px;
				border-color: #7C1313;">
			<p style="margin-top: 14px; margin-left: 90%; color: #7C1313;"><a href="about.php"><b>About Us</b></a>
			</div>
			
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

main article {
	display: inline-block;
	background: #E2BE9E;
	width: 40%;
	border-left-style: solid;
	border-width: 30;
	border-color: #7C1313;
	margin: 20;
	vertical-align:top;
	height: 750px;
}

main article p {
	margin-left: 30;
	margin-right: 30;
	text-align: justify;
}

main li {
	display: inline-block;
	width: 150;
	margin-left: 10;
	margin-right: 10;
	margin-top: 13;
	text-align: center;
}

main li a:link {
	text-decoration: none;
}

main li a:hover {
	color: #7C1313;
}

main p a:link {
	text-decoration: none;
}

main p a:hover {
	color: #7C1313;
}

main button {
	background: #E2BE9E;
	border: none;
}

</style>