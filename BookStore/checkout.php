<?php
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
    $orderID = 0;
    $sql = "SELECT orderID FROM orders";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $orderID = $row['orderID'];
        }
    }
    $orderID++;
    $sql = "SELECT * FROM shoppingcart INNER JOIN book on shoppingcart.isbn = book.isbn";
    $result = $conn->query($sql);
    $itemNumber = 1;
    $orderValue = 0;
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $isbn = $row['ISBN'];
            $price = $row['price'];
            $orderValue += $price;
            
            $sql = "INSERT INTO orderitem VALUES(
                $itemNumber,
                $price,
                $isbn,
                $orderID
            )";
            $result1 = $conn->query($sql);
            $itemNumber++;
            
        }
    }
    $orderDate = date("m/d/Y");
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
    $sql = "INSERT INTO orders VALUES(
        $orderID,
        '$orderDate',
        $orderValue,
        $customerID
    )";
    $result = $conn->query($sql);
    $sql = "DELETE FROM shoppingcart WHERE isbn";
    $result = $conn->query($sql);
    $conn->close();
    echo "done";
?>