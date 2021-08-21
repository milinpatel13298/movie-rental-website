<?php
	$server = "localhost";
        $user = "root";
        $pwd = "*********";
        $db = "mydb";

        $conn = new mysqli($server, $user, $pwd, $db);
        if ($conn->connect_error) {
	   die("Connection failed: " . $conn->connect_error);
        }
	$api_key='6d9fd3ce21081227994c8e8fb0c72c25';
?>