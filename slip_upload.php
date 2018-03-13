<?php
 header('content-type : bitmap; charset=utf-8');
		            $post_image = "";
					
					echo "uploading $post_image";
					
		           $target_dir = "uploads/";
					$target_file = $target_dir . basename($_FILES["slip"]["name"]);
					 $file_temp = $_FILES["image"]["tmp_name"];
					$uploadOk = 1;
					$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
					// Check if image file is a actual image or fake image
					
					if(empty($target_file)){
						echo '  <script type="text/javascript">';
						echo '     alert("Please select a slip");';
						echo "   window.location.href = window.location.href;";
						echo '	 </script>';
					}
					// Allow certain file formats
					if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
					&& $imageFileType != "gif" ) {
						echo '  <script type="text/javascript">';
						echo '     alert("Sorry, only JPG, JPEG, PNG & GIF or non empty files are allowed..");';
						echo "   window.location.href = window.location.href;";
						echo '	 </script>';
						$uploadOk = 0;
					}
					// Check if $uploadOk is set to 0 by an error
					if ($uploadOk == 0) {
						
							echo '  <script type="text/javascript">';
							echo '     alert("Sorry, your file was not uploaded.");';
							echo "   window.location.href = window.location.href;";
							echo '	 </script>';
					// if everything is ok, try to upload file
					} else {
						include "connect.php";
						$mem_no = $_COOKIE['mem_no'];
						
						$sqli = "select don_code from don where mem_no = $mem_no order by don_code desc";
						$resulti = mysqli_query($conn,$sqli);
						$row = mysqli_fetch_array($resulti,MYSQLI_NUM);
						
						$slip_name = $row[0]."-".$mem_no."".$post_image;

						$slip_id = get_slip_id() + 1;
						//$paying = get_slip_rec($mem_no);
						$today = date('y-m-d');
										
						$target_file = "uploads/$slip_name.jpg";
						if (move_uploaded_file($_FILES["slip"]["tmp_name"], $target_file)) {
							if(!slip_exisists("$target_file")){
								$sql = "insert into slip (slip_id,slip_name,poster,receiver,slip_status,slip_date) values($slip_id,'$slip_name','$mem_no','$post_image','pending','$today')" ;
								$result = mysqli_query($conn,$sql);
							}
							echo "errorf ".mysqli_error($conn);
							echo '<script type="text/javascript">';
							echo 'alert("your deposit slip have been successfully uploaded please wait for your payment to be confirmed");';
							echo "   window.location.href = window.location.href;";
							echo '	 </script>';
						} else {
							echo '  <script type="text/javascript">';
							echo '     alert("Sorry, there was an error uploading your slip. please make sure that your images does not exceed 1.9MB.");';
		               		echo "   window.location.href = window.location.href;";
							echo '	 </script>';
							
						}
					}
				}
			
			
			
			function slip_exisists($slip_name){
					include "connect.php";
					$sql = "select * from slip where slip_name = $slip_name and slip_status = 'pending'";
					
					$result = mysqli_query($conn,$sql);
					
					if($result){
							return true;
					}
					else{
						return false;
					}
				}
				function get_slip_id(){
					include "connect.php";
					$sql = "select slip_id from slip order by slip_id desc";
					
					$result = mysqli_query($conn,$sql);
					
					if(mysqli_num_rows($result) > 0){
							$row = mysqli_fetch_array($result,MYSQLI_NUM);
							return $row[0];
					}
					else{
						return 1;
					}
					
				}
				
				
				function get_slip_rec($mem_noi){
					include "connect.php";
					$sql = "select mem_no from pay_request, req_hist where paying member = '$mem_noi' and pay_request.request_code = req_hist.request_code";
					
					$result = mysqli_query($conn,$sql);
					
					if(mysqli_num_rows($result) > 0){	
							$row = mysqli_fetch_array($result,MYSQLI_NUM);
							return $row[0];
					}
					else{
						return '404';
					}
					
				}
	 }
?>