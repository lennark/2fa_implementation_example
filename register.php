<?php

// Config file
require_once('conf/conf.php');
// Include DB connection singleton class
include('common/dbConn.php');
require_once('smsverify.php');
require_once('utils.php');

session_start();

$utils = new Utils();

// Check which registration step we're at
if (isset($_POST['reg-step'])) {
    $step = (int) $_POST['reg-step'];
} elseif (isset($_GET['reg-step'])) {
    $step = (int) $_GET['reg-step'];
} elseif (isset($_SESSION['registration']) && isset($_POST['status'])) {
    // Check SMS verification response
    $step = 3;
} else {
    $step = 1;
}

$dbh = null;

// Check if form was submitted and username is available
if (isset($_POST['btn-register']) && $step === 1) {
    
    try {
        $dbh = dbConn::getConnection();
    }
    catch (Exception $e) {
        error_log('Unable to connect to database [' . $e->getMessage() . ']');
        die('Database connection error: ' . $e->getMessage());
    }
    
    $query    = "SELECT username FROM users WHERE username = :username";
    $username = trim($_POST['reguser']);
    $stmt     = $dbh->prepare($query);
    
    error_log('Checking if \'' . $username . '\' exists');
    
    $stmt->execute(array(
        ':username' => $username
    ));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
    
    // Check if query returned anything
    if ($count == 0) {
        $password     = trim($_POST['regpass']);
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        
        $firstname = trim($_POST['regfirstname']);
        $lastname  = trim($_POST['reglastname']);
        $email     = trim($_POST['regemail']);
        $phone     = trim($_POST['regphone']);
        
        
        $_SESSION['registration'] = array(
            'username' => $username,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => $passwordHash,
            'phone' => $phone
        );
        echo 'OK';
        
    } else {
        error_log('Registration failed: \'' . $username . '\' already exists');
        die('This username is already taken!');
    }
    
} elseif (isset($_SESSION['registration']) && $step === 2) {
    
    $sessionData = $_SESSION['registration'];
    
    // Create array of SMS verification widget params
    $request_params = array(
        'user' => MESSENTE_API_USER,
        'version' => MESSENTE_VERIFY_VERSION,
        'callback_url' => MESSENTE_VERIFY_REGISTER_CALLBACK_URL,
        'phone' => $sessionData['phone']
    );
    
    $verify = new SMSverify();
    
    // Generate signature
    $sig = $verify->generateSignature($request_params, MESSENTE_API_PASS);
    
    // Attach signature
    $request_params['sig'] = $sig;
    
    $url = 'https://verify.messente.com?' . http_build_query($request_params);
    
    // Redirect browser to messente
    $utils->redirect($url);
    
} elseif ($step === 3) {
    
    $sessionData = $_SESSION['registration'];
    error_log('Registration\'s last phase for user ' . $sessionData['username'] . ' with verification status ' . $_POST['status']);
    
    $status = $_POST['status'];
    
    // Check if the user was verified via SMS
    if ($status == 'CANCELLED') {
        $utils->redirect('index.php?err=1&action=register');
    } elseif ($status != 'VERIFIED') {
        $utils->redirect('index.php?err=2&action=register');
    }
    
    if ($status == 'VERIFIED') {
        // Check if phone numbers match
        if ($_POST['phone'] != $sessionData['phone']) {
            error_log($sessionData['username'] . ' is authenticating with ' . $_POST['phone'] . ' instead of registered phone ' . $sessionData['phone']);
            $utils->redirect('index.php?err=3&action=register');
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
            
            $username  = $sessionData['username'];
            $password  = $sessionData['password'];
            $firstname = $sessionData['firstname'];
            $lastname  = $sessionData['lastname'];
            $email     = $sessionData['email'];
            $phone     = $sessionData['phone'];
            
            try {
                if(!$dbh) $dbh = dbConn::getConnection();
                
                $query = "INSERT INTO users(username, firstname, lastname, email, password, phone) VALUES(:username, :firstname, :lastname, :email, :password, :phone)";
                $stmt  = $dbh->prepare($query);
                
                $stmt->execute(array(
                    ':username' => $username,
                    ':firstname' => $firstname,
                    ':lastname' => $lastname,
                    ':email' => $email,
                    ':password' => $password,
                    ':phone' => $phone
                ));
                
                
                $_SESSION['registered'] = true;
                $utils->redirect('index.php');
            }
            catch (Exception $e) {
                error_log('Unable to insert user ' . $username . '[' . $e->getMessage() . ']');
                die('Registration failed: ' . $e->getMessage());
            }
            
        } else {
            $utils->redirect('index.php?err=4&action=register');
        }
    }
    
    
} else {
    // Form was not submitted
    die('Please submit valid form!');
}