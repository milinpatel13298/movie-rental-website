<?php
	require('config.php');
	if(isset($_GET['code'])){
		$code=mysqli_real_escape_string($conn,$_GET['code']);
		$verification_query="select *  from verification_status where code='$code'";
		$verification_result=mysqli_query($conn,$verification_query);
		if(mysqli_num_rows($verification_result)==1){
			$verification_data=mysqli_fetch_assoc($verification_result);
			if($verification_data['verified']==1){
				echo "<div class='Form'>
				     	   <h3>Your account is already verified</h3><br/>
					   <p>Click here to return to the <a href='login.php'>login page</a>.</p>
				      </div>";
			}
			else{
				$email=$verification_data['email'];
				$update_query="update verification_status set verified=1 where email='$email'";
				$update_result=mysqli_query($conn,$update_query);
				if($update_result){
					echo "<div class='Form'>
					     	   <h3>Congratulations, your account is verified!</h3><br/>
						   <p>Click here to return to the <a href='login.php'>login page</a>.</p>
					      </div>";
				}
			}
		}
		else{
			echo "<div class='Form'>
			     	   <h3>No user found!</h3><br/>
			     	   <p>Click here to <a href='register.php'>register again</a>.</p>
			      </div>";
		}
	}
	else{
		echo "<div class='Form'>
		     	   <h3>Invlaid verification link!</h3><br/>
		     	   <p>Click here to <a href='register.php'>register again</a>.</p>
		      </div>";
	}
	
?>