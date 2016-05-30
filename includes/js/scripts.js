jQuery(document).ready(function() {

    //Validate register form
    $("#registration").validate({
        rules: {
            reguser: {
                required: true,
                minlength: 3
            },
            regfirstname: {
                required: true
            },
            reglastname: {
                required: true
            },
            regphone: {
                required: true,
                phoneALL: true
            },
            regpass: {
                required: true,
                minlength: 4,
                maxlength: 15
            },
            regpassc: {
                required: true,
                equalTo: '#regpass'
            },
            regemail: {
                required: true,
                email: true
            },
        },
        messages: {
            reguser: "Please enter user name",
            regfirstname: "Please enter your first name",
            reglastname: "Please enter your last name",
            regpass: {
                required: "Please provide a password",
                minlength: "Password should have at least 8 characters"
            },
            regemail: "Please enter a valid email address",
            regphone: {
                required: "Please insert your phone number",
                phoneALL: "Allowed format: +3725123456"
            },
            regpassc: {
                required: "Please retype password",
                equalTo: "Password doesn't match!"
            }
        },
        submitHandler: submitRegistrationForm
    });


    // Fullscreen background
    $.backstretch("includes/img/backgrounds/bg.jpg");

    // Validate login form
    $(".login-form").validate({

        rules: {
            password: {
                required: true,
                minlength: 4
            },
            username: {
                required: true
            },
        },
        messages: {
            password: {
                required: "Please enter your password"
            },
            username: "Please enter your username",
        },
        submitHandler: submitLoginForm
    });


    // Login form validation
    $('.login-form input[type="text"], .login-form input[type="password"], .login-form textarea').on('focus', function() {
        $(this).removeClass('input-error');
    });

    $('.login-form').on('submit', function(e) {

        $(this).find('input[type="text"], input[type="password"], textarea').each(function() {

            if ($(this).val() == "") {
                e.preventDefault();
                $(this).addClass('input-error');
            } else {
                $(this).removeClass('input-error');
            }
        });

    });

    // Registration form validation
    $('.registration-form input[type="text"], .registration-form input[type="password"]').on('focus', function() {
        $(this).removeClass('input-error');
    });

    $('.registration-form').on('submit', function(e) {

        $(this).find('input[type="text"], input[type="password"]').each(function() {
            if ($(this).val() == "") {
                e.preventDefault();
                $(this).addClass('input-error');
            } else {
                $(this).removeClass('input-error');
            }
        });

    });


    // On Click SignUp It Will Hide Login Form and Display Registration Form
    $("#signup").click(function() {
        $("#sign-in").slideUp("slow", function() {
            $("#sign-up").slideDown("slow");
        });
    });
    // On Click SignIn It Will Hide Registration Form and Display Login Form
    $("#signin").click(function() {
        $("#sign-up").slideUp("slow", function() {
            $("#sign-in").slideDown("slow");
        });
    });


    // login submit
    function submitLoginForm() {
        var data = $(".login-form").serialize();

        $.ajax({
            type: 'POST',
            url: 'login.php',
            data: data,
            beforeSend: function() {
                $("#loginerror").fadeOut();
                $("#loginerror").empty();
                $("#btn-login").html('<span class="glyphicon glyphicon-transfer"></span> &nbsp; Processing ...');
            },
            success: function(response) {
                if (response == 'OK') {
                    $("#btn-login").html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> &nbsp; Verifying ...');
                    window.location.href = "login.php?auth-step=2";
                } else {

                    $("#loginerror").fadeIn(1000, function() {
                        $("#loginerror").html('<div class="alert alert-danger"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp; ' + response + ' </div>');
                        $("#btn-login").html('<span class="glyphicon glyphicon-log-in"></span> &nbsp; Sign In');
                    });
                }
            }
        });
        return false;
    }

    // registration submit
    function submitRegistrationForm() {
        var data = $(".registration-form").serialize();

        $.ajax({
            type: 'POST',
            url: 'register.php',
            data: data,
            beforeSend: function() {
                $("#registererror").fadeOut();
                $("#registererror").empty();
                $("#btn-register").html('<span class="glyphicon glyphicon-transfer"></span> &nbsp; Processing ...');
            },
            success: function(response) {
                if (response == 'OK') {
                    $("#btn-register").html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> &nbsp; Verifying ...');
                    window.location.href = "register.php?reg-step=2";
                } else {

                    $("#registererror").fadeIn(1000, function() {
                        $("#registererror").html('<div class="alert alert-danger"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp; ' + response + ' </div>');
                        $("#btn-register").html('Sign me up!');
                    });
                }
            }
        });
        return false;
    }

});
