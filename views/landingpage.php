<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login</title>

        <!-- Styling -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="includes/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="includes/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="includes/css/style.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
		
        <link rel="shortcut icon" href="includes/ico/favicon.ico">

    </head>

    <body>

        <!-- Top content -->
        <div class="top-content">
        	
            <div class="inner-bg">
                <div class="container">
					
					<div id="sign-in" style="display:<?= $action ? $action == 'login' ? 'block' : 'none' : 'block;'?>">
					
						<div class="row">
							<div class="col-sm-8 col-sm-offset-2 text">
								<h1><strong>Secure</strong> Login</h1>
								<div class="description">
									<p>
										Logging in has never been more secure with 2-step verification! Try it out with this example application. 
										Read more from <a href="http://messente.com/documentation/verification-widget"><strong>messente.com</strong></a> and stay secure!
									</p>
								</div>
							</div>
						</div>
						
						<div id="loginerror" class="col-sm-6 col-sm-offset-3">
						<!-- error will be shown here ! -->	
						<?php
							if($err && !$_SESSION['registration']) {									
								include('views/verify_error.php');
							} elseif($_SESSION['registration'] && $_SESSION['registered'] == true) {
								include('views/success.php');
								
								// These session parameters are no longer needed.
								unset($_SESSION['registration']);
								unset($_SESSION['registered']);
								
							}
						?></div>
						
						<div class="row">
							<div class="col-sm-6 col-sm-offset-3 form-box">
								<div class="form-top">
									<div class="form-top-left">
										<h3>Login to our site</h3>
										<p>Enter your username and password to log on:</p>
									</div>
									<div class="form-top-right">
										<i class="fa fa-key"></i>
									</div>
								</div>							
								
								
								<div class="form-bottom">
									<form role="form" method="post" class="login-form" action="">
										<div class="form-group">
											<label class="sr-only" for="username">Username</label>
											<input type="text" name="username" placeholder="Username" class="form-username form-control" id="username">
										</div>
										<div class="form-group">
											<label class="sr-only" for="password">Password</label>
											<input type="password" name="password" placeholder="Password" class="form-password form-control" id="password">
										</div>
										<input type="hidden" name="auth-step" value="1">
										<button type="submit" class="btn" name="btn-login" id="btn-login">Sign in!</button>
									</form>
								</div>
							</div>
						</div>
						
					<div class="ask-login-signup">
						<h3>...not a member yet?</h3>
						<div class="ask-login-signup-buttons">
							<a class="btn btn-link-1 btn-link-1-login" href="#" id="signup">
								<i class="fa fa-pencil"></i> Sign up
							</a>		                        	
						</div>
					</div>
						
					</div><!-- Sign-in -->	
					
						
						
						<div id="sign-up" style="display:<?= $action ? $action == 'register' ? 'block' : 'none' : 'none;'?>">
						
							<div class="row">
								<div class="col-sm-8 col-sm-offset-2 text">
								<h1><strong>Secure</strong> registration</h1>
									<div class="description">
									<p>
										Verify new users by SMS! Try it out with this example application. 
										Read more from <a href="http://messente.com/documentation/verification-widget"><strong>messente.com</strong></a> and stay secure!
									</p>
									</div>
								</div>
							</div>
							
							<div id="registererror" class="col-sm-6 col-sm-offset-3">
									<!-- error will be shown here ! -->	
									<?php
										if($err) {											
											include('views/verify_error.php');
										}
									?>
									</div>
								
								
								<div class="row">									
									<div class="col-sm-6 col-sm-offset-3 form-box">
										<div class="form-top">
											<div class="form-top-left">
												<h3>Sign up now</h3>
												<p>Fill in the form below to get instant access:</p>
											</div>
											<div class="form-top-right">
												<i class="fa fa-pencil"></i>
											</div>
										</div>
										
										<div class="form-bottom">
											<form id="registration" role="form" action="" method="post" class="registration-form">											
												<div class="form-group">
													<label class="sr-only" for="reguser">Username</label>
													<input type="text" name="reguser" placeholder="Username" class="form-username form-control" id="reguser">
												</div>
												<div class="form-group">
													<label class="sr-only" for="regfirstname">First name</label>
													<input type="text" name="regfirstname" placeholder="First name" class="form-first-name form-control" id="regfirstname" >
												</div>
												<div class="form-group">
													<label class="sr-only" for="reglastname">Last name</label>
													<input type="text" name="reglastname" placeholder="Last name" class="form-last-name form-control" id="reglastname" >
												</div>
												<div class="form-group">
													<label class="sr-only" for="regpass">Password</label>
													<input type="password" name="regpass" placeholder="Password" class="form-password form-control" id="regpass" >
													
												</div>
												<div class="form-group">
													<label class="sr-only" for="regpassc">Confirm password</label>
													<input type="password" name="regpassc" placeholder="Confirm password" class="form-password-confirm form-control" id="regpassc" >										
													
												</div>
												<div class="form-group">
													<label class="sr-only" for="regemail">Email</label>
													<input type="text" name="regemail" placeholder="Email" class="form-email form-control" id="regemail" >
												</div>											
													
												<div class="form-group">													
													<label class="sr-only" for="regphone">Phone number</label>
													<input type="text" name="regphone" placeholder="Phone with country prefix" class="form-phone-nr form-control bfh-phone" id="regphone" >
												</div>
												<input type="hidden" name="reg-step" value="1" >											
												<button type="submit" class="btn" name="btn-register" id="btn-register">Sign me up!</button>
											</form>
										</div>
										
									</div><!-- form-box -->										
								</div><!-- row -->
								
								<div class="ask-login-signup">
									<h3>...already a member?</h3>
									<div class="ask-login-signup-buttons">
										<a class="btn btn-link-1 btn-link-1-login" href="#" id="signin">
											<i class="fa fa-sign-in"></i> Log in
										</a>		                        	
									</div>
								</div>
						
						</div><!-- Sign up form -->
						
						
                </div>
            </div>
        </div>


        <!-- Javascript includes -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<script src="includes/js/jquery.backstretch.min.js"></script>
		<script src="includes/js/jquery.validate.min.js"></script>
		<script src="includes/js/additional-methods.js"></script>
        <script src="includes/js/scripts.js"></script>
        
        <!--[if lt IE 10]>
            <script src="includes/js/placeholder.js"></script>
        <![endif]-->

    </body>

</html>