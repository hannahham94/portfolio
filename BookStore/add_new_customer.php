<?php
    $fname = $_POST['fName'];
    $lname = $_POST['lName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
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
    $sql = "SELECT customerID FROM customer";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $customerID = $row['customerID'];
        }
    }
    $customerID++;
    
    $sql = "DELETE FROM logged_in WHERE customerID";
    $result = $conn->query($sql);
    $sql = "INSERT INTO customer VALUES(
        $customerID,
        '$fname',
        '$lname',
        $customerID,
        $customerID
    )";
    $result = $conn->query($sql);
    
    if($result === false)
    {
        echo "customer not added";
        exit();
    }
    $sql = "INSERT INTO credentials VALUES(
        $customerID,
        '$customer_username',
        '$customer_password'
    )";
    $result = $conn->query($sql);
    if($result === false)
    {
        echo "username or password already exists.";
        exit();
    }
    $sql = "INSERT INTO contactDetails VALUES(
        $customerID
    )";
    $result = $conn->query($sql);
    $sql = "INSERT INTO email VALUES(
        $customerID,
        '$email'
    )";
    $result = $conn->query($sql);
    if($result === false)
    {
        echo "email already exists";
        exit();
    }
    $sql = "INSERT INTO phone VALUES(
        $customerID,
        '$phone'
    )";
    $result = $conn->query($sql);
    if($result === false)
    {
        echo "phone already exists";
        exit();
    }
    $sql = "INSERT INTO address VALUES(
        $customerID,
        '$address'
    )";
    $result = $conn->query($sql);
    if($result === false)
    {
        echo "address already exists";
        exit();
    }
    $sql = "INSERT INTO loggedin VALUES($customerID)";
    $result = $conn ->query($sql);
    //echo $fname . " " . $lname . " added as a new customer.";
    echo true;
    
    $conn->close();
?>