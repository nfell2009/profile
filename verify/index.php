<?php

	if(!isset($_GET['c']) || empty($_GET['c'])) header("Location: https://profiles.ac3-servers.eu/");
	
	session_start();
	include_once("../includes/user_functions.php");
	
	if(isset($_SESSION['user']) && isset($_SESSION['pass']) && validUser($_SESSION['user'], $_SESSION['pass'], true)){
		header("Location: https://profiles.ac3-servers.eu/api/");
	}
	
	header('Refresh: 5; URL=https://profiles.ac3-servers.eu/');
	$complete = array();
	
	if(verify($_GET['c']))
		$complete[] = "<strong>Sucessfully verified!</strong>";
	else
		$complete[] = "Either the account you are attempting to verify was deleted, never existed, or has already been verified.";
	
	echo("<body><h3>You will be redirected...</h3>");
	echo("<ul>");
	foreach($complete as $val) echo("<li>$val</li>");
	echo("</ul>");
