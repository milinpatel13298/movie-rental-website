<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Registration</title>
    <link rel="stylesheet" href="styles.css"/>
</head>
<body>
<?php
    require('config.php');
    session_start();

    if (isset($_POST['submit'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $register_query = "insert into users values('$username','$email','$password')";
        $register_result = mysqli_query($conn, $register_query) or trigger_error("Query Failed! SQL: $register_query - Error: ".mysqli_error($conn), E_USER_ERROR);;
	if($register_result){
	    $code=mysqli_real_escape_string($conn,bin2hex(random_bytes(13)));
	    $verification_query="insert into verification_status values('$email',0,'$code')";
	    $verification_result=mysqli_query($conn, $verification_query)or trigger_error("Query Failed! SQL: $verification_query - Error: ".mysqli_error($conn), E_USER_ERROR);
            if ($verification_result) {
	        $to=$email;
	        $subject="Milin's Movie Rental Service | Email Verification";
	        $message="
	                 Hi, ".$username."! Please click this link to verify your account."."\r\n".
		         "https://patelm.ursse.org/verify-user.php?code=".$code.
                "";
	        $headers="From:milin@patelm.ursse.org"."\r\n";
	        $mail_delivery=mail($to,$subject,$message,$headers);
	        if($mail_delivery==1){
	           echo "<div class='Form'>
                             <h3>Congratulations, your account has been created successfully!</h3><br/>
			     <p>A verification link has been sent to your registered email. Please click on the link to complete your verification.</p>
                             <p>Click here to return to the <a href='login.php'>login page</a>.</p>
                         </div>";
                   //$_SESSION['username']=$username;
                   //header('location:index.php');
                }
                else{
                    "<div class='Form'>
                         <h3>Error sending verification link to your email</h3><br/>
                         <p>Click here to <a href='register.php'>register again</a>.</p>
                     </div>";
                }
            }
            else{
	         "<div class='Form'>
                      <h3>Error generating a verification code for your account</h3><br/>
                      <p>Click here to <a href='register.php'>register again</a>.</p>
                  </div>";
            }
         }
         else {
               echo "<div class='Form'>
                         <h3>Sorry, your account could not be created</h3><br/>
                         <p>Click here to <a href='register.php'>register again</a>.</p>
                     </div>";
         }
    }
    else {
?>
    <form method="post" name="register">
        <h2 align="left">Create an account</h2>
        <input type="text" name="username" placeholder="Enter your desired username" required /><br/>
        <input type="text" name="email" placeholder="Enter a valid email address"required><br/>
        <input type="password" name="password" placeholder="Enter your password" required><br/>
        <input id="button" type="submit" name="submit" value="Register">
        <p align="center">Already have an account?&nbsp;<a href="login.php">Click to login</a></p>
    </form>
<?php
    }
?>
</body>
</html>
