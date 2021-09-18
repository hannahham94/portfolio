<?php
/* This is mostly done and barely tested. It worked on 12/1/19
The modify will add a + for spaces.(please fix)*/
error_reporting(0);
	ini_set('display_errors', 0); //Hides errors caused by $check
$link = mysqli_connect("localhost", "root", "", "bookstore");
$read = "readonly";
$orderID = $_GET["orderID"];
$dis = "disabled";
$form = $_GET["form"];
$orderDate = ""; $orderValue = ""; $customerID = "";
if($orderID != ""){
	$sql="SELECT * FROM orders WHERE $orderID = '$orderID';";
	$result = mysqli_query($link,$sql);
	if($row = mysqli_fetch_array($result)) {
		$orderDate=$row["orderDate"];$orderValue=$row["orderValue"];$customerID=$row["customerId"];
		$date=explode("/",$orderDate);
		if(strlen($date[0]) == 1 ){
			$date[0]="0".$date[0];
		}
		if(strlen($date[1]) == 1 ){
			$date[1]="0".$date[1];
		}
		$cDate=$date[2] . "-" . $date[0] . "-" . $date[1];
		$dis="";
	}
	else{
		$isbn="That this does not exist.";
	}
	
}
	//$sql = "SELECT * FROM assigned,category WHERE ISBN = '$isbn' AND assigned.ISBN = category.categoryCode;";
	//$result = mysqli_query($link,$sql);
	//if($row = mysqli_fetch_array($result)) {
?>
<br />
<form action="modifyindex.php?id=&form=">
		<div style="margin-top: 25;
				text-align: center;
				background: #E2BE9E;
				margin-left: 30;
				margin-right: 15%;
				width: 95%;
				height: 350px;
				border-style: dashed;
				border-radius: 7px;
				border-color: #7C1313;
				display:inline-block;">
				
				<br />
				<br />
				Order ID: <input type="text" name="orderID" id="orderID" value="<?php echo $orderID;?>" /><br /><br />
							<input name="form" value="orders" hidden />
				Order Date: <input type="date" value="<?php echo $cDate;?>" readonly /> <br /><br />
				Order Value: <input type="text" value="<?php echo $orderValue;?>" readonly /> <br /><br />
				Customer ID: <input type="text" value="<?php echo $customerID;?>" readonly /> <br /><br />
				<input type = "submit" value="Get Record" style = "color: #E2BE9E; border-color: #7C1313; background: #7C1313; border-radius: 4px;">
				<input type = "submit" id="del" formaction="delete.php" value="Delete Record" style="margin-left: 40px;
				color: #E2BE9E; border-color: #7C1313; background: #7C1313; border-radius: 4px;" />
				<input type = "submit" value="Modify Record" style="margin-left: 40px;
				color: #E2BE9E; border-color: #7C1313; background: #7C1313; border-radius: 4px;" disabled />
				
		</div>
</form>
		<!--
		<br />
		<br />
		<div>
		<form action="" id="modifyDB">
		<input type = "delete" name = "delete" value="Delete">
		<input type = "modify" name = "modify" value="Modify">
		</form>
		</div>-->
		
		</main>
	</body>
	
</html>

<script type="text/javascript">
	document.getElementById("orderID").oninput = function() {
		document.getElementById("del").disabled=true;
	}
</script>


<style type="text/css">


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

</style>