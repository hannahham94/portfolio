<?php
    $customerID = $_POST['customerID'];
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
    $sql = "DELETE FROM loggedIn WHERE customerID";
    $result = $conn->query($sql);
    $sql = "DELETE FROM customer WHERE customerID = '$customerID'";
    $result = $conn->query($sql);
    $sql = "DELETE FROM credentials WHERE customerID = '$customerID'";
    $result = $conn->query($sql);
    
    $sql = "DELETE FROM contactDetails WHERE contactID = '$customerID'";
    $result = $conn->query($sql);
    $sql = "DELETE FROM email WHERE contactID = '$customerID'";
    $result = $conn->query($sql);
    $sql = "DELETE FROM phone WHERE contactID = '$customerID'";
    $result = $conn->query($sql);
    $sql = "DELETE FROM address WHERE contactID = '$customerID'";
    $result = $conn->query($sql);
    echo $customerID . "'s account successfully deleted.";
    
    $conn->close();
?>