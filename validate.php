<?php
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
			
	function send_mail($to,$usernme,$password,$user){
			$subject = "Member signup confirmation";
                        $from = "member@unlimitedfunds.co.za";
                        
			 $message = "
			 <html>
				 <head>
				 <title>confirm</title>
				 </head>
				 <body>
				 <h3>Hello $user</h3><br>
				 <h3>Welcome to unlimited funds</h3>
				 <h4>Please see your login details below</h4>
				 <table>
				 <tr>
				 <th>username:</th>
				 <th>$usernme</th>
				 </tr>
				 <tr>
				 <td>password:</td>
				 <td>$password</td>
				 </tr>
				 </table>
				 
				 <h4>Thank you for choosing unlimited funds.</h4><br><br>
				 <h4>Kind Regards</h4>
				 <h4>unlimited funds</h4>
				 </body>
			 </html>
			 ";

			 // Always set content-type when sending HTM
			
			$headers = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
			$headers .= "From: unlimited funds <$from>\r\n";
			 
			  if (mail($to,$subject,$message,$headers)) { 
			            echo "<script type='text/javascript'>alert('You have successfully created an unlimited account, please proceed to a login screen.')</script>";
			        } else { 
			            echo "<script type='text/javascript'>alert('opps something went wrong, please contact wealthzone network administrators for help')</script>";
			        }
	}
	
	function get_mem_number(){
		require "connect.php";
		$sql = "SELECT mem_no from mem order by mem_no desc";
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_array($result);
		
		if(mysqli_num_rows($result) > 0){
				return $row[0];
		}
        else{
			return 1000;
		}
	}
	
	function email_exists($email,$mem){
		require "connect.php";
		include "protect-data.php";
		
		$sql = "SELECT email from mem where mem_no = '$mem'";
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_array($result,MYSQLI_NUM);
		
		$w_email = $row[0];
		
		if($w_email == $email){
			echo "email equal";
				//return true;
		}
        else{
			echo "email not equal";
			//return false;
		}
	}
	
	function number_exists($num,$mem){
		require "connect.php";
		include "protect-data.php";
		echo "calling phone";
		$sql = "SELECT phone from mem where mem_no = '$mem'";
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_array($result,MYSQLI_NUM);
		
		$w_phone = decrypt($row[0]);
		
		if($w_phone == $num){
				echo "number equal";
				//return true;
		}
        else{
			echo "number not equal";
			//return false;
			
		}
	}
	
	if(isset($_POST['signup'])) 
	{
		$fname     = protect($_POST["fname"]);
		$sname     = protect($_POST["lname"]);
		$email     = strtolower(protect($_POST["email"]));
		$ref_email = protect($_POST["remail"]);
		$gender    = protect($_POST["gender"]);
		$phone     = protect($_POST["cell"]);
		$password  = protect($_POST["chpassword"]);
		$mem_no    = protect((get_mem_number() + 1));
		$date      = date("Y-m-d");
		
		$sql = "SELECT phone from mem";
		$result = mysqli_query($conn, $sql);
		$log = true;
		
		while($row = mysqli_fetch_array($result,MYSQLI_NUM)){
		
		$w_phone = decrypt($row[0]);
		
                 
                 if($w_phone == $phone){
			echo "<script type='text/javascript'>alert('This cellphone number already exists, please use a different cellphone number');</script>";
			
			$log = false;
			break;

			}
		}
		
		$sql = "SELECT email from mem";
		$result = mysqli_query($conn, $sql);
		
		while($row = mysqli_fetch_array($result,MYSQLI_NUM)){
		
		$w_email = $row[0];
		
                 
		if($w_email == $email){
			echo "<script type='text/javascript'>alert('This email already exists, please use a different email');</script>";
			
			$log = false;
			break;
			}
		}
		
		if($log){
		$e_mem_no = encrypt(strtolower("$mem_no"));
		$e_name = encrypt(strtolower("$fname"));
		$e_surname = encrypt(strtolower("$sname"));
		$e_email = encrypt(strtolower("$email"));
		$e_phone = encrypt("$phone");
		$e_password = encrypt("$password");
		$e_gender = encrypt(strtolower("$gender"));
		$e_ref_email = encrypt(strtolower("$ref_email"));
		$e_join_date = encrypt("$date");
		$e_status = encrypt("active");
		
		$sql = "INSERT INTO mem 
		                   (mem_no,name,surname,email,phone,password,gender,ref_email,join_date,status) 
						    VALUES('$mem_no','$e_name','$e_surname','$e_email','$e_phone','$e_password','$e_gender','$e_ref_email','$e_join_date','$e_status')";
		$sqli = "INSERT INTO acb (mem_no) values('$mem_no')"; 
		
		if (mysqli_query($conn, $sql) && mysqli_query($conn, $sqli)) {
			
				mysqli_commit($conn);
				
				echo "<script type='text/javascript'>"; 
				echo "alert('You have successfully created a wealthzone network account, please login using your choosen email address and password.');";
				echo " window.location='login.php';";
				echo "</script>";
			
			} else {
				echo "<script type='text/javascript'>alert('opps something went wrong, please contact wealthzone network administrators for help')</script>";
			}
		}
		else{
				echo "<script type='text/javascript'>"; 
				echo "  window.location='register.php';";
				echo "</script>";
				
			}
	}
	else if(isset($_POST['login'])){
		require "connect.php";
		
		$email     = encrypt(strtolower(protect($_POST["memno"])));
		$password   = protect($_POST["password"]);
		
		$sql = "SELECT * from mem where email = '$email'";
		$sql2 ="SELECT password from mem where email = '$email'";
		$sql3 ="SELECT mem_no from mem where email = '$email'";
		 
		$result = mysqli_query($conn, $sql);
		$result2 = mysqli_query($conn, $sql2);
		$result3 = mysqli_query($conn, $sql3);
		
		$row = mysqli_fetch_array($result,MYSQLI_NUM);
		$row2 = mysqli_fetch_array($result2,MYSQLI_NUM);
		$row3 = mysqli_fetch_array($result3,MYSQLI_NUM);
		
		$online_pass = decrypt($row2[0]);
		
		$fname     = $row[1];
		$sname     = $row[2];
		
		if($password == $online_pass){
			$rlink = 'http://wealthnetworkzone.co.za/register.php?usermail='.decrypt($email);
			$names     = decrypt($fname);
			
			echo "<script type='text/javascript'>"; 
			echo "  setCookie('mem_no','$row3[0]',1000);";
			echo "  setCookie('names','$names',1000);";
			echo "  setCookie('ref','$rlink',1000);";
			echo "  window.location='pages/platform.php';";
			echo "</script>";
			$display = "none";
		
				}
		else{	
    		$display = "block";

		}
		
	}
	
	
?>