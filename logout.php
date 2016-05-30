<?php session_start();
	unset($_SESSION['details']);
	unset($_SESSION['authenticated']);
	session_destroy();
	die(header("Location:index.php"));?>