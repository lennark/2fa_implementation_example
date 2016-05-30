<?php
// Initialize logging
if(ini_get("log_errors") != 1) {
	ini_set("log_errors", 1);
}

if(ini_get("log_errors") != LOG_PATH) {
	ini_set("error_log", LOG_PATH);
}

// Required files
require_once('conf/conf.php');
require_once('utils.php');

$utils = new Utils();

// Start session
session_start();

// Check if user is already logged in
if(isset($_SESSION['authenticated'])) {
	$utils->redirect('home.php');
}
// Check if error was raised
if(isset($_POST['err'])) {
	$err = $_POST['err'];
} elseif(isset($_GET['err'])) {
	$err = $_GET['err'];
}

// Check action
if(isset($_POST['action'])) {
	$action = $_POST['action'];
} elseif(isset($_GET['action'])) {
	$action = $_GET['action'];
}

// Set error message
if($err) {
	switch($err) {
		case 1:
			$err = 'User verification was cancelled!';
			break;
		case 2:
			$err = 'Verification failed!';
			break;
		case 3:
			$err = 'Different phone number was used for verification!';
			break;
		case 4:
			$err = 'Verification signatures do not match!';
			break;
		default:
			$err = 'Oops! Unknown error!';
			break;
	}
}

// Add landingpage view
require_once('views/landingpage.php');
?>
