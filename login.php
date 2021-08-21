<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Login</title>
    <link rel="stylesheet" href="styles.css"/>
</head>
<body>
<?php
    require('config.php');
    session_start();

    if (isset($_POST['submit'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
	$query="select * from users join verification_status on users.email=verification_status.email where username='$username'";
	$result=mysqli_query($conn,$query);
	if(mysqli_num_rows($result)==1){
	    $data=mysqli_fetch_assoc($result);
	    if($data['verified']==0){
	        echo "<div class='Form'>
			   <h3>You need to verify your account first!</h3><br/>
                           <p>Click the verification link in your email and try to <a href='login.php'>login again</a>.</p>
                      </div>";
	    }
            else{
		if ($username=$data['username'] && $password=$data["password"]) {
			   $_SESSION['username'] = $data['username'];
			   header('Location:index.php');
			   die();
                }
		else {
			   echo "<div class='Form'>
			             <h3>Incorrect Username/password.</h3><br/>
                                     <p>Click here to <a href='login.php'>try again</a>.</p>
                                 </div>";
                }
	    }
	}
    }			   
    else{
	?>
	<form method="post" name="login">
             <h2 align="left"">Login</h2>
             <input type="text" name="username" placeholder="Enter your username" required/>
             <input type="password" name="password" placeholder="Enter your password" required/>
             <input id="button" type="submit" value="Login" name="submit" />
             <p align="center">Don't have an account?<a href="register.php">&nbsp;Register here</a></p>
        </form>
<?php
    }
?>
</body>
</html>
