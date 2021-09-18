<?php
	error_reporting(0);
	ini_set('display_errors', 0); //Hides errors caused by $check
	$link = mysqli_connect("localhost", "root", "", "bookstore");
	$idType = explode("?",$_SERVER['REQUEST_URI']);
	$idType = explode("=",$idType[1]);
	$id = $_GET["$idType[0]"];//gets id from url
	$form=$_GET["form"];			//gets form from url
	$check=$_GET["yes"];			//gets yes from the yes button below.
	$del=$_GET["del"];
	$ISBNs = array();
	echo $del;
	if($check == "yes"){
		//echo "this is done";
		$sql="DELETE FROM $form WHERE $id = $idType[0];";
		$result=mysqli_query($link, $sql);
		if($form == "book") {
			$sql = "DELETE FROM assigned WHERE $id = $idType[0];";
			$result=mysqli_query($link, $sql);
			$sql = "DELETE FROM writtenby WHERE $id = $idType[0];";
			$result=mysqli_query($link, $sql);
			$sql = "DELETE FROM series WHERE $id = $idType[0];";
			$result=mysqli_query($link, $sql);
		}
		if($form == "author") {
			$ints = 0;
			$sql = "SELECT ISBN FROM writtenby WHERE $id = $idType[0];";
			if($result = mysqli_query($link, $sql)) {
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result)) {
						$ISBNs[$ints] = $row['ISBN'];
						$ints += 1;
					}
				}
			}
			for($i = 0; $i < count($ISBNs); ++$i) {
				$sql = "DELETE FROM book WHERE ISBN = $ISBNs[$i];";
				$result=mysqli_query($link, $sql);
				$sql = "DELETE FROM series WHERE ISBN = $ISBNs[$i];";
				$result=mysqli_query($link, $sql);
				$sql = "DELETE FROM writtenby WHERE ISBN = $ISBNs[$i];";
				$result=mysqli_query($link, $sql);
				$sql = "DELETE FROM assigned WHERE ISBN = $ISBNs[$i];";
				$result=mysqli_query($link, $sql);
			}
		}
	}
?>

<html>
<body onload="goBack()">
	<form>
		<div id="check"></div>
		<input id="id" name=<?php echo $idType[0];?> value="<?php echo $id;?>" hidden />
		<input id="form" name="form" value="<?php echo $form;?>" hidden />
		<input id="deleted" name="del" value="deleted" hidden />
		<input id="del" value="<?php echo $del;?>" hidden />
		Are you sure you wish to delete <?php echo $id;?>?
		<input onclick="back()" type="submit" name="yes" value="yes" />
		<input onclick="justGoBack()"type="submit" value="no" formaction= "modifyindex.php" />
	</form>
	<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
	<script>
		function back(){
			alert("It has been deleted");
		}
		function goBack(){
			if(document.getElementById("del").value == "deleted"){
				window.location.href = 'modifyindex.php';
			}
		}
		function justGoBack(){
			document.getElementById("id").setAttribute("value","");
			document.getElementById("form").setAttribute("value","");
		}
	</script>
</body>
</html>