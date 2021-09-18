<?php
    $isbn = $_POST['isbn'];
    $orderID = $_POST['orderID'];
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
    $sql = "DELETE FROM orderitem WHERE ISBN = '$isbn' and orderID = '$orderID'";
    $result = $conn->query($sql);
    $sql = "SELECT * FROM orderitem WHERE orderID = '$orderID'";
    $result = $conn->query($sql);
    if($result->num_rows == 0)
    {
        $sql =  "DELETE FROM orders WHERE orderID = '$orderID'";
        $result = $conn->query($sql);
    }
    $conn->close();
?>