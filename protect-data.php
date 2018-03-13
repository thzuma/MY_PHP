<?php
	//$key = md5($password);
	//$salt = md5($password);
	
	function protect($input){
	    include 'connect.php';
    	$input = mysqli_real_escape_string($conn,$input);
		return $input;
	} 
	
	function encrypt($input){
		$key = 'notmd5notmd5mdmd';
		$input = rtrim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256,$key,$input,MCRYPT_MODE_ECB)));
		return $input;
	}
	
	function decrypt($input){
		$key = 'notmd5notmd5mdmd';
		$input = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$key,base64_decode($input),MCRYPT_MODE_ECB));
		return $input;
	}
	
	function hashword($input,$salt){
		$input = crypt($input,'$1$'.$salt.'$');
		return $input;
	}
	
?>