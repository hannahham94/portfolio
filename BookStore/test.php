<?php
$title;
$author;
?>

<html>
<body>
<form name='form' method='post' action='index.php'>
Title String: <input type="text" name="title" id="input"><br/>
Author String: <input type="text" name="author" id="input"><br/>
<input type="submit" name="submit" value="Submit"><br/>
Posted String:<input type="text" name="output" id="postedOut" value=<?php
if((isset($_POST['title'])) && !empty($_POST['title']))
{
	$title = $_POST['title'];
	echo $title;
}


?>><br/>
</form>
</body>
</html>