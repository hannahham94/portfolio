<?php
    $first = $_POST['first'];
    $last = $_POST['last'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $user_username = $_POST['username'];
    $user_password = $_POST['password'];
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
    $sql = "SELECT * FROM loggedIn";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $customerID = $row['customerID'];
        }
    } else {
        echo "0 results";
    }
    $sql = "UPDATE customer SET first='$first', last='$last' WHERE customerID = '$customerID'";
    $result = $conn->query($sql);
    $sql = "UPDATE address SET addressValue='$address' WHERE contactID = '$customerID'";
    $result = $conn->query($sql);
    $sql = "UPDATE email SET emailValue='$email' WHERE contactID = '$customerID'";
    $result = $conn->query($sql);
    $sql = "UPDATE phone SET phoneValue='$phone' WHERE contactID = '$customerID'";
    $result = $conn->query($sql);
    $sql = "UPDATE credentials SET username='$user_username', password='$user_password' WHERE customerID = '$customerID'";
    $result = $conn->query($sql);
    
    echo $result === true;
    $conn->close();
?>