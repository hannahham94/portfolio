<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bookstore";
    // Create connection
    $conn = mysqli_connect($servername, $username, $password);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "CREATE DATABASE bookstore";
    $result = $conn->query($sql);
    $conn->close();
    
    $names_file = fopen("names.txt", "r") or die("Unable to open file!");
    $address_file = fopen("addresses.txt", "r") or die("Unable to open file!");
    $titles_file = fopen("BookTitles.txt", "r") or die("Unable to open file!");
    $gender_ary = array("Female", "Male", "non-binary");
    $category_ary = array("Young Adult", "Classic", "Romance", "Children", "Non-Fiction", "Biography");
    $supplier_ary = array("Simon & Schuster", "HarperCollins", "Penguin Books", "MacMillan Publishers");
	$organization_ary = array("CBS Corporation", "News Corp", "Penguin Random House", "Holtzbrinck Publishing Group");
	$series_ary = array("Magic Sword", "Red Velvet", "The Merry Mansion", "Haunted Toad", "John's Adventures", "The Fluffy Orb", "Sixty Nights", "Jerky Jerry", "Dr. Drew's Diary", "The Kinky Kaleidoscope");
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
	
    $sql = "CREATE TABLE address(
        contactID INT,
        addressValue varchar(100),
        PRIMARY KEY(contactID, addressValue))";
    $result = $conn->query($sql);
    $sql = "CREATE TABLE assigned(
        ISBN BIGINT,
        categoryCode INT,
        PRIMARY KEY(ISBN, categoryCode))";
    $result = $conn->query($sql);
    $sql = "CREATE TABLE author(
        first text,
        last text,
        gender text,
        DoB varchar(10),
        authorID INT PRIMARY KEY,
        contactID INT,
        sAuthorID INT)";
    $result = $conn->query($sql);
    
    $sql = "CREATE TABLE book(
        ISBN BIGINT PRIMARY KEY,
        pubDate varchar(10),
        price double,
        title text,
        userReview INT,
        sName text)";
    $result = $conn->query($sql);
    $sql = "CREATE TABLE category(
        categoryCode INT PRIMARY KEY,
        categoryDescription text)";
    $result = $conn->query($sql);
    $sql = "CREATE TABLE contactDetails(
        contactID INT PRIMARY KEY)";
    $result = $conn->query($sql);
    $sql = "CREATE TABLE credentials(
        customerID INT,
        username varchar(100),
        password varchar(100),
        PRIMARY KEY (customerID, username, password)
    )";
    $result = $conn->query($sql);
    $sql = "CREATE TABLE customer(
        customerID INT PRIMARY KEY,
        first text,
        last text,
        sCustomerID INT,
        contactID INT)";
    $result = $conn->query($sql);
    $sql = "CREATE TABLE email(
        contactID INT,
        emailValue varchar(100),
        PRIMARY KEY(contactID, emailValue))";
    $result = $conn->query($sql);
    $sql = "CREATE TABLE loggedIn(
        customerID INT PRIMARY KEY
    )";
    $result = $conn->query($sql);
    $sql = "CREATE TABLE orderItem(
        itemNumber INT,
        itemPrice double,
        ISBN BIGINT,
        orderID INT,
		PRIMARY KEY(itemNumber, orderID))";
    $result = $conn->query($sql);
    $sql = "CREATE TABLE orders(
        orderID INT PRIMARY KEY,
        orderDate varchar(100),
        orderValue text,
        customerId INT)";
    $result = $conn->query($sql);
    $sql = "CREATE TABLE phone(
        contactID INT,
        phoneValue BIGINT,
        PRIMARY KEY(contactID, phoneValue))";
    $result = $conn->query($sql);
    $sql = "CREATE TABLE shoppingcart(
        ISBN BIGINT PRIMARY KEY
    )";
    $result = $conn->query($sql);
    $sql = "CREATE TABLE supplier(
        Name varchar(100) PRIMARY KEY)";
    $result = $conn->query($sql);
    $sql = "CREATE TABLE supplierrep(
        first varchar(100),
        last varchar(100),
        ID INT,
        organization varchar(100),
        email text,
        cellNumber BIGINT,
        workNumber BIGINT,
        sName text,
        PRIMARY KEY(first, last, ID, organization))";
    $result = $conn->query($sql);
    $sql = "CREATE TABLE writtenby(
        ISBN BIGINT,
        authorID INT,
        PRIMARY KEY(ISBN, authorID))";
    $result = $conn->query($sql);
	$sql = "CREATE TABLE series(
		ISBN BIGINT,
		seriesName text)";
	$result = $conn->query($sql);
    //create authors
    for($i = 0; $i < 10; $i++)
    {
        $name = explode(" ", fgets($names_file));
        $fname = $name[0];
        $lname = substr($name[1], 0, strlen($name[1]) - 2);
        $gender = $gender_ary[array_rand($gender_ary)];
        $Dob = mt_rand(1, 12) . "/" . mt_rand(1, 28) . "/" . mt_rand(1500, 2000);
        $authorId = $i + 1000;
        $contactId = $i + 1000;
        $sql = "INSERT INTO author VALUES(
            '$fname',
            '$lname',
            '$gender',
            '$Dob',
            $authorId,
            $contactId,
            $authorId)";
        $result = $conn->query($sql);
        $sql = "INSERT INTO contactDetails VALUES(
            $contactId
        )";
        $result = $conn->query($sql);
        $email = $lname . $fname . "@gmail.com";
        $sql = "INSERT INTO email VALUES(
            $contactId,
            '$email'
        )";
        $result = $conn->query($sql);
        $phone_num = mt_rand(1000000000,9999999999);
        $sql = "INSERT INTO phone VALUES(
            $contactId,
            $phone_num
        )";
        $result = $conn->query($sql);
        $address = fgets($address_file);
        $address = substr($address, 0, strlen($address) -  2);
        $sql = "INSERT INTO address VALUES(
            $contactId, '$address')";
        $result = $conn->query($sql);
    }
    $authors_ary = array();
    $sql = "SELECT * FROM author";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            array_push($authors_ary, $row['authorID']);
        }
    } else {
        echo "0 results";
    }
    //create books
    for($i = 0; $i < 200; $i++)
    {
        $title = fgets($titles_file);
        $title = substr($title, 0, strlen($title) -  2);
		$month = mt_rand(1, 12);
		if(strlen($month) == 1) {
			$month = "0" . $month;
		}
		$day = mt_rand(1, 28);
		if(strlen($day) == 1) {
			$day = "0" . $day;
		}
        $pubDate = $month . "/" . $day . "/" . mt_rand(1500, 2000);
        $price = mt_rand(10, 100) + .99;
        $isbn = ($i * 11111111) + 2145938462;
        $user_review = mt_rand(1, 5);
		$addSeries = mt_rand(0, 1);
        $sName = $supplier_ary[array_rand($supplier_ary)];
        $sql = "INSERT INTO book VALUES(
            $isbn,
            '$pubDate',
            $price,
            '$title',
            '$user_review',
            '$sName')";
        $result = $conn->query($sql);
        $cat_code = mt_rand(1, 6);
        $sql =  "INSERT INTO assigned VALUES(
            $isbn,
            $cat_code
        )";
        $result =  $conn->query($sql);
        $authorId = $authors_ary[array_rand($authors_ary)];
        $sql = "INSERT INTO writtenby VALUES(
            $isbn,
            $authorId
        )";
        $result =  $conn->query($sql);
    }
	//fill series
	{
		$ints = 0;
		$sql = "SELECT * FROM author";
		if($result = mysqli_query($conn, $sql)) {
			if(mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_array($result)) {
					$authors[$ints] = $row['first'] . " " . $row['last'];
					$ints += 1;
				}
			}
		}
		for($i=0; $i<10; ++$i){
			$author = $authors[$i];
			$ints=0;
			$sql = "SELECT ISBN FROM author INNER JOIN writtenby ON writtenby.authorID = author.authorID WHERE CONCAT(author.first, ' ', author.last) = '$author'";
			if($result = mysqli_query($conn, $sql)) {
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result)) {
						$ISBNs[$ints] = $row['ISBN'];
						$ints += 1;
					}
				}
			}
			
			$series = $series_ary[$i];
			
			for($k = 0; $k < count($ISBNs); ++$k) {
				$sql = "SELECT categoryCode FROM assigned WHERE ISBN = '$ISBNs[$k]'";
				$result =  $conn->query($sql);
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result)) {
						$categoryCodes[$k] = $row['categoryCode'];
					}
				}
			}
			
			for($j=0; $j<$ints; ++$j) {
				$isbn = $ISBNs[$j];
				if($j % 2 == 0 && $categoryCodes[$j] == $categoryCodes[0]) {
					$sql = "INSERT INTO series VALUES (
						$isbn,
						'$series'
					)";
					$result =  $conn->query($sql);
				}
			}
		}
	}
	
    //create categories
    for($i = 0; $i < 6; $i++)
    {
        $cat_code = $i + 1;
        $category_description = $category_ary[$i];
        $sql = "INSERT INTO category VALUES(
            $cat_code,
            '$category_description'
        )";
        $result = $conn->query($sql);
    }
	//create admins
	for($i = 0; $i < 5; $i++) {
		$admin_id = $i + 3000;
		$name = explode(" ", fgets($names_file));
        $fname = $name[0];
        $lname = substr($name[1], 0, strlen($name[1]) - 2);
		$username = $lname . $fname . mt_rand(10, 99);
		$password = "admin";
		$sql = "INSERT INTO credentials VALUES(
            $admin_id,
            '$username',
            '$password'
        )";
        $result = $conn->query($sql);
	}
    //create customers
    for($i = 0; $i < 10; $i++)
    {
        $cus_id = $i + 2000;
        $name = explode(" ", fgets($names_file));
        $fname = $name[0];
        $lname = substr($name[1], 0, strlen($name[1]) - 2);
        $contactId = $i + 2000;
        $sql = "INSERT INTO customer VALUES(
            $cus_id,
            '$fname',
            '$lname',
            $cus_id,
            $contactId
        )";
        $result = $conn->query($sql);
        $sql = "INSERT INTO contactDetails VALUES(
            $contactId
        )";
        $result = $conn->query($sql);
        $email = $lname . $fname . "@gmail.com";
        $sql = "INSERT INTO email VALUES(
            $contactId,
            '$email'
        )";
        $result = $conn->query($sql);
        $phone_num = mt_rand(1000000000,9999999999);
        $sql = "INSERT INTO phone VALUES(
            $contactId,
            $phone_num
        )";
        $result = $conn->query($sql);
        $address = fgets($address_file);
        $address = substr($address, 0, strlen($address) -  2);
        $sql = "INSERT INTO address VALUES(
            $contactId, '$address')";
        $result = $conn->query($sql);
        $username = $lname . $fname . mt_rand(10, 99);
        $password = "Password";
        $sql = "INSERT INTO credentials VALUES(
            $cus_id,
            '$username',
            '$password'
        )";
        $result = $conn->query($sql);
    }
    //create supplier
    foreach($supplier_ary as $sup_name)
    {
        $sql = "INSERT INTO supplier VALUES('$sup_name')";
        $result =  $conn->query($sql);
    }
    //create supplier rep
    for($i = 0; $i < 10; $i++)
    {
        $sup_id = $i + 3000;
        $name = explode(" ", fgets($names_file));
        $fname = $name[0];
        $lname = substr($name[1], 0, strlen($name[1]) - 2);
        $email = $lname  . $fname . "@gmail.com";
        $cellNumber =  mt_rand(1000000000,9999999999);
        $workNumber =  mt_rand(1000000000,9999999999);
		$index = mt_rand(0,3);
        $sName = $supplier_ary[$index];
		$organization = $organization_ary[$index];
        $sql = "INSERT INTO supplierRep VALUES(
            '$fname',
            '$lname',
            $sup_id,
            '$organization',
            '$email',
            $cellNumber,
            $workNumber,
            '$sName'
        )";
        $result = $conn->query($sql);
    }
    $conn->close();
    fclose($names_file);
    fclose($address_file);
    fclose($titles_file);
    echo "Process Complete";
?>