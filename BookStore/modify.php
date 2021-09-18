<?php
	$link = mysqli_connect("localhost", "root", "", "bookstore");
	error_reporting(0);
	ini_set('display_errors', 0); //Hides errors caused by $check
	$uri = $_SERVER['REQUEST_URI'];
	$form = $_GET['form'];
	$serverVars= explode("&",str_replace("?","&",$uri));
	$date = strpos($uri,"-");	
	$ids = explode("=",$serverVars[1]);		//gets id from url
	$sql="UPDATE $form SET ";
	if($form != "supplierrep"){
	for($x = count($serverVars) - 1; $x > 2;$x--){
		if($date!== false){
			$a=explode("=",$serverVars[$x]);
			$dateTemp = explode("-",$a[1]);
			$dateFinal=$dateTemp[1] . "/" . $dateTemp[2] . "/" . $dateTemp[0];
			$var=explode("=",$serverVars[$x]);
			$sql=$sql . "$var[0]='$dateFinal'";
			$date="";
		} else{
			$var=explode("=",$serverVars[$x]);
			if(strpos($var[1],"+")!== false){
				$var[1]=str_replace("+"," ",$var[1]);
			}
			$sql=$sql . "$var[0]='$var[1]'";
		}
		 $x--;
		 if($x > 2){
			 $sql = $sql . ", ";
		 }
		 $x++;
	}
	$sql = $sql . " WHERE $ids[0] = $ids[1];";
	$result=mysqli_query($link , $sql);
	}
	else{
		$ids = explode("=",$serverVars[1]);
		$id2 = explode("=",$serverVars[5]);
		if(strpos($id2[1],"+")!== false){
				$id2[1]=str_replace("+"," ",$id2[1]);
			}
		$id3 = explode("=",$serverVars[3]);
		$id4 = explode("=",$serverVars[4]);
		echo count($serverVars);
		for($x = count($serverVars) - 1; $x > 5;$x--){
			if($date!== false){
				$a=explode("=",$serverVars[$x]);
				$dateTemp = explode("-",$a[1]);
				$dateFinal=$dateTemp[1] . "/" . $dateTemp[2] . "/" . $dateTemp[0];
				$var=explode("=",$serverVars[$x]);
				$sql=$sql . "$var[0]='$dateFinal'";
				$date="";
			} else{
				$var=explode("=",$serverVars[$x]);
				if(strpos($var[1],"+")!== false){
					$var[1]=str_replace("+"," ",$var[1]);
				}
				$sql=$sql . "$var[0]='$var[1]'";
			}
			 $x--;
			 if($x > 5){
				 $sql = $sql . ", ";
			 }
			 $x++;
		}
		$sql = $sql . " WHERE $ids[0] = '$ids[1]' AND $id2[0] = '$id2[1]' AND $id3[0] = '$id3[1]' AND $id4[0] = '$id4[1]';";
		echo $sql;
		$result=mysqli_query($link , $sql);
		}
?>

<html>
<body onload="goBack()">
	
	<script>
		function goBack(){
			alert("It has been updated");
			//window.location.href = 'index.php?id=&form=';
		}
	</script>
</body>
</html>