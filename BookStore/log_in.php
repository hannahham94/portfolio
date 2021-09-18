<html>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">.
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet"
href="https://fonts.googleapis.com/css?family=Lobster" />
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
        <div style="margin-top: 10%;
		text-align: center;
		background: #E2BE9E;
		margin-left: 25%;
		margin-right: auto;
		width: 50%;
		height: 50%;
		border-style: dashed;
		border-radius: 7px;
		border-color: #7C1313;
		display:inline-block;">
            <div id="divi">
                <input type="text" id="username" placeholder="Username..."><br>
				<input type="password" id="password" placeholder="Password..."><br>
				<input type="submit" id="loginButton" value="Login" onclick="checkCreds()" style="margin-top:40px; color: #E2BE9E; border-color: #7C1313; background: #7C1313;"><br>
				<br />
				<a href = "sign_up.php" id="link" style="text-decoration: none; border: 2px outset #7C1313; padding: 3px;
														color: #E2BE9E; border-color: #7C1313; background: #7C1313; border-radius: 4px;
				">Sign Up</a>
        </div>
	</body>
</html>
<script>
    function checkCreds()
    {
		var uname = document.getElementById("username").value;
		var psswrd = document.getElementById("password").value;
        $.ajax({
			type: "POST",
			url: "check_credentials.php",
			data: "username=" + uname + "&password=" + psswrd,
			success:  function(data) {
				if(data != "")
				{
					if(data < 3000)
					{
						window.location = "customerProfile.php";
					}
					else
					{
						window.location = "admin.php";
					}
					
				}
				else
				{
					alert("Incorrect username or password");
				}
			},
			error: function() {
				alert("failure");
			}
			});
    }
</script>
<style type="text/css">
#divi{
    margin-top:10%;
}
input{
    border-radius:5px;
    margin-top:25px;
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