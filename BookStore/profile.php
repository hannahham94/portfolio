<html>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

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
			<input type="submit" id="logoutButton" value="Logout" onclick="logout()" style="margin-bottom:10px;"><br>
	</div>
		
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
	font-family: helvetiva, arial, sans-serif;
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