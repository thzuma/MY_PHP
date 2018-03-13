<!DOCTYPE html>
<html lang="en">
    
<head>
        <title>Login</title><meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="css/maruti-login.css" />
    </head>
    <body>
	<?php
			echo "<script type='text/javascript'>";
			echo " function setCookie(cname, cvalue, exdays) {";
			echo "	var d = new Date();";
			echo "	d.setTime(d.getTime() + (exdays*1000));";
			echo "	var expires = 'expires='+ d.toUTCString();";
			echo "	document.cookie = cname + '=' + cvalue + ';' + expires;";
			echo "  }";
			echo " </script>";
	?>
	    <div id="loginbox">            
            <form id="loginform" method="post" class="form-vertical" action="login.php">
				<?php
					if(isset($_POST["login"])){
			  		  include "connect.php";
					  include "protect-data.php";
		              
					$username    = strtolower(protect($_POST["username"]));
					$password    = protect($_POST["password"]);
					
					$sql = "SELECT * from member where mem_name = '$username'";
					$sql2 ="SELECT password from member where mem_name = '$username'";
					$sql3 ="SELECT mem_id from member where mem_name = '$username'";
					 
					 $result = mysqli_query($conn, $sql);
					 $result2 = mysqli_query($conn, $sql2);
					 $result3 = mysqli_query($conn, $sql3);
					 
					if(mysqli_num_rows($result) > 0 ){
					
					$row = mysqli_fetch_array($result,MYSQLI_NUM);
					$row2 = mysqli_fetch_array($result2,MYSQLI_NUM);
					$row3 = mysqli_fetch_array($result3,MYSQLI_NUM);
					
					$online_pass = $row2[0];
					
					$fname     = $row[1];
					$sname     = $row[2];
					
						if($password == $online_pass){
							echo "<script type='text/javascript'>"; 
							echo "  setCookie('mem_no','$row3[0]',1000);";
							echo "  window.location='index.php'";
							echo "</script>";
						
								}
						else{	
							echo "<p style='color: red; font-size: bold; text-align: center;'>invalid password</p>";
						    }
					  }
					  else{
						  echo "<p style='color: red; font-size: bold; text-align: center;'>invalid username</p>";
						  }
					}
				    else{
					  //echo "not logging in";
					  }
				?>
		
				<div class="control-group normal_text"> <h3><img src="img/logo.png" alt="Logo" /></h3></div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on"><i class="icon-user"></i></span><input type="text" placeholder="Username" name="username" id="username"/>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on"><i class="icon-lock"></i></span><input type="password" placeholder="Password" name="password" id="password"/>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <span class="pull-left"><a href="#" class="flip-link btn btn-inverse" id="to-recover">Lost password?</a></span>
                    <span class="pull-right"><input type="submit" class="btn btn-success" name="login" value="Login" /></span>
					</div>
            </form>
            <form id="recoverform" action="#" class="form-vertical">
				<p class="normal_text">Enter your e-mail address below and we will send you instructions how to recover a password.</p>
				
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on"><i class="icon-envelope"></i></span><input type="text" placeholder="E-mail address" />
                        </div>
                    </div>
               
                <div class="form-actions">
                    <span class="pull-left"><a href="#" class="flip-link btn btn-inverse" id="to-login">&laquo; Back to login</a></span>
                    <span class="pull-right"><input type="submit" class="btn btn-info" value="Recover" /></span>
                </div>
            </form>
        </div>
        
        <script src="js/jquery.min.js"></script>  
        <script src="js/maruti.login.js"></script> 
    </body>

</html>
