<?php
	require("helpers.php");
	session_start();
	if (!in_array($_SERVER["PHP_SELF"], ["/login.php", "/register.php"])) {
		if(empty($_SESSION["logged_in"]))
		{
	    	header( 'Location: login.php' );
	    	exit;
		}
	}
?>