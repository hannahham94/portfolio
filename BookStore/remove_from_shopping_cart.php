<?php
$isbn = $_POST['isbn'];
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
$sql = "DELETE FROM shoppingcart WHERE ISBN = '$isbn'";
$result = $conn->query($sql);
?>