<?php

		
		function get_data($query){
			include "connect.php";
			
			$result = mysqli_query($conn, $query);
			if($result){
				$rown = mysqli_fetch_array($result,MYSQLI_NUM);
				return $rown;
			}
			else{
				return;
			}			
		}
		
		function put_data(array $values, $table){
			include "connect.php";
			$mem_no = $_COOKIE["mem_no"];
			   
			switch($table){
				case "acb": insert_acb($values); break;
				case "adm": insert_adm($values); break;
				case "don": insert_don($values); break;
				case "faq": insert_faq($values); break;
				case "first_con": insert_first($values); break;
				case "mem": insert_acb($values); break;
				case "pay_request": insert_pay($values); break;
				case "req_hist": insert_req($values); break;
				case "slip": insert_slip($values); break;
			}
		}
		
		
		function get_seq(){
			include "connect.php";
			$seq_sql = "select * from adm where pay_ind = 'Y'";
			$seq_result = mysqli_query($conn, $seq_sql);
			$row = mysqli_fetch_array($seq_result,MYSQLI_NUM);
			if(mysqli_num_rows($seq_result) > 0){
				$seq = $row[4];
			}
			else{
				$seq = 1;
			}  
			return $seq;
		   }
             
	   function insert_don(){
			include "connect.php";
			$mem_no = $_COOKIE["mem_no"];
			
			if($mem_no == '1001A' || $mem_no == '1001B' || $mem_no == '1001C' || $mem_no == '1001D'){
				echo "<script type='text/javascript'>alert('Administrators are not allowed to place orders');</script>";
				echo "<script type='text/javascript'>window.location.href = window.location.href;</script>";
				return;
					
				}
				
				//check if banking details are uploaded in the system
			$sql = "SELECT IFNULL(bank_name,''),IFNULL(account_no,''),IFNULL(branch_code,''),IFNULL(account_type,'') from acb where mem_no = '$mem_no' ";
			        
			$result = mysqli_query($conn, $sql);
			$banking_d = mysqli_fetch_array($results,MYSQLI_NUM);
			$check1 = decrypt($banking_d[0]);
			$check2 = decrypt($banking_d[1]);
			$check3 = decrypt($banking_d[2]);
			$check4 = decrypt($banking_d[3]);
			
			if($check1 = '' || $check2 = '' || $check3 = '' || $check4 = ''){
			    echo "<script type='text/javascript'>alert('Please upload your banking details before placing an order');</script>";
				echo "<script type='text/javascript'>window.location.href = window.location.href;</script>";
				return;
				
			}
					
			
			//check if memeber have already made a donation or not
					$sql = "SELECT * from don where mem_no = '$mem_no' and don_status = 'pending'";
					$result = mysqli_query($conn, $sql);
					
					$sqli = "SELECT * from req_hist where paying_member = '$mem_no' and status = 'pending'";
					$resulti = mysqli_query($conn, $sqli);
					
					
					if(mysqli_num_rows($result) > 0 || mysqli_num_rows($resulti) > 0){
						echo "<script type='text/javascript'>alert('please make a payment to the last donation you made, if you already made a payment please wait for the payment to be confirmed');</script>";
					    echo "<script type='text/javascript'>window.location.href = window.location.href;</script>";
						return;
					}
					
				  else{
					$don_amt = $_POST["sel1"]; 
					$don_date = date("y-m-d");
					$don_status = "pending";
					$expected_pay = ($don_amt * 0.3) + $don_amt; 
					$don_date_to = date_format(date_add(date_create($don_date.""),date_interval_create_from_date_string("15 days")),"y-m-d");
					$current_pay = 0;
					
					//getting the next don_code
					$sql = "select * from don order by don_code asc";
					$result = mysqli_query($conn, $sql);
					$row = mysqli_fetch_array($result,MYSQLI_NUM);
					if(mysqli_num_rows($result) > 0){
						$don_code = $row[4]+1;
					}
					else{
						$don_code = 1;
					}
					//create a donation entry
					
					insert_first($don_amt);
					
			$sql = "insert into don(mem_no,don_amt,don_date,don_status,don_code,don_date_to,expected_pay,current_pay)
         					values('$mem_no',$don_amt,'$don_date','$don_status',$don_code,'$don_date_to',$expected_pay,$current_pay)";
			
			$query = mysqli_query($conn, $sql);
			
			$sql = "insert into award(mem_no,award,award_status,award_date)
         					values('$mem_no',$expected_pay,'pending','$don_date_to')";
			
			$award_query = mysqli_query($conn, $sql);
			
			if($query && $award_query){
					
						echo "<script type='text/javascript'>alert('Your donation was successfully created, please make payment to the given banking details below')</script>";
						  
						  //##############ASSIGN A PAYMENT######################
							$amount_topay = 0;
							check:
							$sql = "SELECT * from pay_request where request_status = 'pending' and mem_no <> '$mem_no' order by request_code asc";
							$result = mysqli_query($conn, $sql);
							$rowr;
							$requester;
							$amt_prom = 0;
							//check if there exist a payment request 
							if(mysqli_num_rows($result) > 0){
								
								$rowr = mysqli_fetch_array($result,MYSQLI_NUM);
								
								$sqlh = "SELECT * from req_hist 
								         where status = 'pending' 
										 and requester <> '$mem_no' 
										 and request_code = $rowr[1]";
										 
								$resulth = mysqli_query($conn, $sqlh);
								
								if($resulth){
									while($proms = mysqli_fetch_array($resulth,MYSQLI_NUM)){
											$amt_prom = $amt_prom + $proms[5];
										}
								}	
								
								if($amt_prom == 0)
							    	$amount_topay = $rowr[3];
							    else
									$amount_topay = $rowr[3] - $amt_prom;
								
								
								$rec_code = $rowr[1];
								$requester = $rowr[0];
											
								$Dday = date("d");
								$Dmon = date("m");
								$Dyer = date("y");
								
								$reference = 'R$don_amt$Dday$Dmon$Dyer';
								
								if($don_amt < $amount_topay ){
									$sql = 	"insert into req_hist(request_code,requester,amount_requested,paying_member,amount_paid,amount_prom,status,reference)
        									values($rec_code,$requester,$rowr[3],'$mem_no',$don_amt,$don_amt,'pending','r423DDsA')";
			
									$result = mysqli_query($conn, $sql);
									
									
								}
								else if($don_amt == $amount_topay){
									$sql = 	"insert into req_hist(request_code,requester,amount_requested,paying_member,amount_paid,amount_prom,status)
        									values($rowr[1],$requester,$rowr[3],'$mem_no',$amount_topay,$don_amt,'pending')";
									$result = mysqli_query($conn, $sql);
									
									$sql = 	"update pay_request set request_status = 'confirmed' where request_code = $rowr[1]";
									$result = mysqli_query($conn, $sql);
									
									
								}
								else{
									$sql = 	"insert into req_hist(request_code,requester,amount_requested,paying_member,amount_paid,amount_prom,status)
        									values($rowr[1],$requester,$rowr[3],'$mem_no',$amount_topay,$don_amt,'pending')";
									$result = mysqli_query($conn, $sql);
									
									$sql = 	"update pay_request set request_status = 'confirmed' where request_code = $rowr[1]";
									$result = mysqli_query($conn, $sql);
										
									$don_amt = abs($don_amt - $amount_topay);
									goto check;
								}
								
						   }
						   else{
							   //pay us
							  
							    $today = date('y-m-d');
								$rowr = mysqli_fetch_array($result,MYSQLI_NUM);
								$next_seq = get_seq();
								$don_amt = abs($don_amt);
								$adm = get_data("select * from adm where pay_ind = 'Y'",$conn);
								$bank = get_data("select * from acb where mem_no = $adm[0]",$conn);
								
								$update = upd_rec("update adm set pay_ind = 'N' where seq_no = $next_seq",$conn);
								
								if($next_seq == 4)
									$next_seq = 1;
								else
									$next_seq++;
								
								$update = upd_rec("update adm set pay_ind = 'Y' where seq_no = $next_seq",$conn);
								
								$req_no = get_request_number();
								
								$sql = 	"insert into pay_request(mem_no,request_code,request_status,amt_requested,amt_paid,req_date)
        									           values('$adm[0]',$req_no,'confirmed','$don_amt','$don_amt','$today')";
									$result = mysqli_query($conn, $sql);
								
								$sql = 	"insert into req_hist(request_code,requester,amount_requested,paying_member,amount_paid,amount_prom,status,reference)
        									           values($req_no,'$adm[0]','$don_amt','$mem_no',$don_amt,$don_amt,'pending','adm_pending')";
									$result = mysqli_query($conn, $sql);
								
									$sql = 	"update pay_request set request_status = 'confirmed' where request_code = $rowr[1]";
									$result = mysqli_query($conn, $sql);
									
						   }
						   echo "<script type='text/javascript'>window.location.href = window.location.href;</script>";
					}
					else{
						echo "<script type='text/javascript'>alert('Your donation was not successfull')</script>";
					}
				}
			}
		
		function upd_rec($query,$connection){
   					    $results = mysqli_query($connection, $query);
						if(!$results){
							return false;
						}
						
					 return true; 
		}
		
		
		function insert_first($don_amt){
     		   include "connect.php";
			   $mem_no = $_COOKIE["mem_no"];
			   
			   $query = "select * from first_cont where mem_no = '$mem_no'";
			   $day = date("Y-m-d");
			   $mem_no = $_COOKIE["mem_no"];
			   
			   if (!get_data($query)){
				   $query = "select mem_no from mem where email = (select ref_email from mem where mem_no = '$mem_no')";
				   $rrow = get_data($query);
			
				$query = "insert into first_cont (mem_no,contribution,con_date,rec_mem_no,status) values('$mem_no',$don_amt,'$day','$rrow[0]','unpaid')";
				$seq_result = mysqli_query($conn, $query);
				echo mysqli_error($conn);
				return;
			   }
		}
		
		
		function insert_slip(array $values){
			include "connect.php";
			$mem_no = $_COOKIE["mem_no"];
			   
		}
		
		
		
			
		function provide(array $values){
				$mem_no = $_COOKIE["mem_no"];
			     insert_don($values);  
		}
		
		
		function get_help(){
			include "connect.php";
			$mem_no = $_COOKIE["mem_no"];			
			
			$date_month = (date("m"))	;
			$date_day = (date("d"))	;
			$date_year = (date("y"))	;
			$date_today = date("m-d-y");
			
			$req_no = get_request_number();
			$request_stat = "pending";
			$amt_req = 0;
			$amt_paid = 0;
			$date_to = "";
			
			$sql = "SELECT * from award where mem_no = '$mem_no' and award_status = 'pending'";
			$result = mysqli_query($conn, $sql);
			
			if(mysqli_num_rows($result) > 0){
				
				$slsql = "select slip_name from slip where poster = '$mem_no' and slip_status = 'confirmed'" ;
				
				$slresult = mysqli_query($conn,$slsql);
				
				if(!mysqli_num_rows($slresult) > 0){
					echo "<script type='text/javascript'>alert('Please post your deposit slip and wait for the payment to be confirmed before you ask for help')</script>";
					return;
				}
				
				
				$row = mysqli_fetch_array($result,MYSQLI_NUM);
				$date_to = $row[3];
				
				$date_to_month = date("m",strtotime($date_to));
				$date_to_day = date("d",strtotime($date_to));
				$date_to_year = date("y",strtotime($date_to));
				
				if(is_requested()){
					echo "<script type='text/javascript'>alert('Your have already made a request');</script>";
						return;
				}
				else if($date_month >= $date_to_month && $date_day >= $date_to_day && $date_year >= $date_to_year){
					$qualify = "select * from first_cont where rec_mem_no = '$mem_no' and status = 'paid' and mem_no <> '$mem_no'";
					$qualify_results = mysqli_query($conn, $qualify);
					$mems = mysqli_num_rows($qualify_results);
				
					if($qualify_results){
					
						$qualify_row = mysqli_fetch_array($qualify_results,MYSQLI_NUM);
					  
					if($mems >= 5){
						$bonus_sql = "select contribution from first_cont where rec_mem_no = '$mem_no' and status = 'unpaid'";
						$bonus_results = mysqli_query($conn, $bonus_sql);
						
						if($bonus_results){
							while($bonus = mysqli_fetch_array($bonus_results,MYSQLI_NUM)){
								$amt_req =  $amt_req + ($bonus[0] * 0.05);
							}
							
							$amt_req = $amt_req + $row[1];
						}
						
						
					  
					}
					else{
						$amt_req = $row[1];
					}
					
					}
					else{
						$amt_req = $row[1];
					}
				
				$sql = "insert into pay_request(mem_no,request_code,request_status,amt_requested,amt_paid,req_date)
										 values('$mem_no',$req_no,'$request_stat',$amt_req,0,'$date_today')";
				$result = mysqli_query($conn, $sql);
						if($result){
							echo "<script type='text/javascript'>alert('Your request have been successfully processed, please wait for a payment')</script>";
							
							$sql = "update don set don_status = 'assigned' where mem_no = '$mem_no' and don_status = 'pending'";
							$result = mysqli_query($conn, $sql);
							
							$sql = "update award set award_status = 'awarded' where mem_no = '$mem_no' and award_status = 'pending'";
							$result = mysqli_query($conn, $sql);
							
							$sql = "update slip set slip_status = 'done' where poster = '$mem_no' and slip_status = 'confirmed'";
							$result = mysqli_query($conn, $sql);
							
							$sql = "update first_cont set status = 'rewarded' where rec_mem_no = '$mem_no' and status = 'paid' and mem_no <> '$mem_no'";
							$result = mysqli_query($conn, $sql);
							}
						else{
											echo "";	 
						}
					
					}
				else{
								 echo "<script type='text/javascript'>alert('Please wait for your payment day');</script>";	
				}
			}
			
			else{
				echo "<script type='text/javascript'>alert('Please make a donation before request a payment');</script>";
			}
			
		  }
		  
			
		function get_request_number(){
				require "connect.php";
				$sql = "SELECT request_code from pay_request order by request_code desc";
				$result = mysqli_query($conn, $sql);
				
				if($result){
					$row = mysqli_fetch_array($result,MYSQLI_NUM);
					return $row[0] + 1;
				}
				else{
					return 1;
				}
			}
				
		function is_requested(){
			require "connect.php";
			$mem_no = $_COOKIE["mem_no"];
			
			$paysql = "SELECT * from pay_request where mem_no = '$mem_no' and request_status = 'pending'";
			$payresult = mysqli_query($conn, $paysql);
			
			if(mysqli_num_rows($payresult) > 0){
				return true;
			}
			else{
				return false;
			}
			}
	

?>