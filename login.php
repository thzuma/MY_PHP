<?php
	function exists($password,$mem){
		require "connect.php";
		
		$sql = "select * from member where mem_id = '$mem' and password = '$password'";
		$result = mysqli_query($conn, $sql);
		
		if($result){
			$row = mysqli_fetch_array($result,MYSQLI_NUM);
			if(mysqli_num_rows($result) > 0){
					return true;
			}
			else{
				return false;
			}
		}
	}
	
	if(isset($_POST['submit_login'])){
	
	include "connect.php";
	include "protect-data.php";
	echo "<script type='text/javascript'>";
	echo " function setCookie(cname, cvalue, exdays) {";
	echo "	var d = new Date();";
	echo "	d.setTime(d.getTime() + (exdays*1000));";
	echo "	var expires = 'expires='+ d.toUTCString();";
	echo "	document.cookie = cname + '=' + cvalue + ';' + expires;";
	echo "  }";
	echo " </script>";
	
	
	$member_id = $_POST['mem_id'];
	$password = $_POST['password'];
	
	if(exists($password,$member_id)){
		echo"exist";
		
		$sql = "select * from member where mem_id = '$member_id' and password = '$password'";
		$result = mysqli_query($conn, $sql);
		
		$name = "";
		$surname = "";
		
		if($result){
			$row = mysqli_fetch_array($result,MYSQLI_NUM);
			$name = $row[1];
			$surname = $row[3];
		}
		
		echo "<script type='text/javascript'>"; 
		echo "  setCookie('mem_no','$member_id',1000);";
		echo "  setCookie('name','$name',1000);";
		echo "  setCookie('surname','$surname',1000);";
		echo "  window.location='index.php';";
		echo "</script>";
	}
	else{
		echo"not exist";
	}
	
	}
?>