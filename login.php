<?php

// Config file
require_once('conf/conf.php');
// Include DB connection singleton class
include('common/dbConn.php');
require_once('smsverify.php');
require_once('utils.php');

session_start();

$utils = new Utils();

// Check which authentication step we're at
if (isset($_POST['auth-step'])) {
    $step = (int) $_POST['auth-step'];    
} elseif (isset($_GET['auth-step'])) {
    $step = (int) $_GET['auth-step'];
} elseif (isset($_SESSION['details']) && isset($_POST['status'])) {
    // Check SMS verification response
    $step = 3;
} else {
    $step = 1;
}

$dbh = null;

// Check if form was submitted
if (isset($_POST['username']) && $step === 1) {
    try {
        $dbh = dbConn::getConnection();
    }
    catch (Exception $e) {
        error_log('Unable to connect to database [' . $e->getMessage() . ']');
        die('Database connection error: ' . $e->getMessage());
    }
    
    $query = "SELECT username, firstname, lastname, password, phone FROM users WHERE username = :username LIMIT 1";
    
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    $stmt = $dbh->prepare($query);
    
    error_log('Checking if \'' . $username . '\' exists');
    
    $stmt->execute(array(
        ':username' => $username
    ));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
    
    $userDetails = array_pop($result);
    
    $stmt->closeCursor();
    
    // Check if query returned anything
    if (!$count > 0) {
        error_log($username . ' not found in database!');
        die('Invalid username!');
    } else {
        $hash = $userDetails["password"];
        
        // 1st step of authentication passed!
        if (password_verify($password, $hash)) {
            error_log($username . ' : valid credentials!');
            $_SESSION['details'] = $userDetails;
            die('OK');
        } else {
            error_log($username . ' : INVALID password!');
            die('Invalid password!');
        }
    }
} elseif (isset($_SESSION['details']) && $step === 2) {
    
    $sessionData = $_SESSION['details'];
    
    // Create array of SMS verification widget params
    $request_params = array(
        'user' => MESSENTE_API_USER,
        'version' => MESSENTE_VERIFY_VERSION,
        'callback_url' => MESSENTE_VERIFY_LOGIN_CALLBACK_URL
    );
	// We do not want to show the registered phone number, otherwise add this
	//'phone' => $sessionData['phone']
    
    $verify = new SMSverify();
    
    // Generate signature
    $sig = $verify->generateSignature($request_params, MESSENTE_API_PASS);
    
    // Attach signature
    $request_params['sig'] = $sig;
	 
    $url = 'https://verify.messente.com?' . http_build_query($request_params);
    
    // Redirect browser to messente
    $utils->redirect($url);
    
} elseif ($step === 3) {
    
    $sessionData = $_SESSION['details'];
    error_log('Authentication\'s last phase for user ' . $sessionData['username'] . ' with verification status ' . $_POST['status']);
    
    $status = $_POST['status'];
    
    // Check if the user was verified via SMS
    if ($status == 'CANCELLED') {
        $utils->redirect('index.php?err=1');
    } elseif ($status != 'VERIFIED') {
        $utils->redirect('index.php?err=2');
    }
    
    if ($status == 'VERIFIED') {
        // Check if phone numbers match
        if ($_POST['phone'] != $sessionData['phone']) {
            error_log($sessionData['username'] . ' is authenticating with ' . $_POST['phone'] . ' instead of registered phone ' . $sessionData['phone']);
            $utils->redirect('index.php?err=3');
        }
        
        // Allowed request parameter keys
        $allowed_keys = array(
            'user',
            'phone',
            'version',
            'callback_url',
            'sig',
            'status'
        );
        
        // Now calculate the signature and compare		
        $verify = new SMSverify();
        
        // Initialize parameters array
        $params = array();
        
        // Add all POST parameters to array for signature comparison
        foreach ($_POST as $key => $value) {
            if (in_array($key, $allowed_keys)) {
                $params[$key] = $value;
            }
        }
        
        // Validate the signature
        if ($verify->verifySignatures($params, MESSENTE_API_PASS)) {
            $_SESSION['authenticated'] = true;
            $utils->redirect('home.php');
        } else {
            $utils->redirect('index.php?err=4');
        }
    }
} else {
    // Form was not submitted
    die('Please submit valid form!');
}
    