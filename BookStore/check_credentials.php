<?php
    $customer_password = $_POST['password'];
    $customer_username =  $_POST['username'];
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
    $sql = "SELECT * FROM credentials";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if($customer_username == $row["username"] && $customer_password == $row["password"])
            {
                $customerID = $row["customerID"];
                $sql = "INSERT INTO loggedIn VALUES($customerID)";
                $result = $conn->query($sql);
                echo $customerID;
                exit();
            }
        }
    }
    $conn->close();
?>