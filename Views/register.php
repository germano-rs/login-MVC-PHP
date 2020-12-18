<body>

    <div class="wrapper fadeInDown">
        <div id="formContent">

            <!-- Icon -->
            <div class="fadeIn first mt-3 mb-4">
                <img src="<?php echo BASE_URL ?>/Public/img/login.svg" id=" icon" width="100" height="100" alt="User Icon" />
            </div>

            <!-- Register Form -->
            <div id="register-alert" style="display:none" class="alert col-sm-12">
                <span class="glyphicon glyphicon-exclamation-sign"></span>
                <span id="message"></span>
            </div>
            <form id="register-form">
                <div>
                    <input type="text" id="name" required autocomplete="false" class="fadeIn second" name="name" autocorrect="off" spellcheck="false" readonly onfocus="this.removeAttribute ('readonly');">
                    <label class="col-12" for="name">Full Name</label>
                    <input type="email" required="true" id="email" autocomplete="false" class="fadeIn second" name="email" autocorrect="off" spellcheck="false" readonly onfocus="this.removeAttribute ('readonly');">
                    <label class="col-12" for="email">E-mail address</label>
                    <input class="input input-group" required type="password" id="password" class="fadeIn third" name="password" autocorrect="off" spellcheck="false" readonly onfocus="this.removeAttribute ('readonly');">
                    <label class="col-12" id="eye" for="password">Password <img id="svgeye" src="<?php echo BASE_URL ?>/Public/img/eye.svg" width="20" height="20" class="mb-1" alt="User Icon" /></label>
                    <input class="input input-group" required type="password" id="password2" class="fadeIn third" name="password2" autocorrect="off" spellcheck="false" readonly onfocus="this.removeAttribute ('readonly');">
                    <label class="col-12" id="eye2" for="password">Confirm Password <img id="svgeye" src="<?php echo BASE_URL ?>/Public/img/eye.svg" width="20" height="20" class="mb-1" alt="User Icon" /></label>
                </div>
                <input id="btn-register" type="submit" class="fadeIn fourth" value="Register">
            </form>
            <!-- Login -->
            <div id="formFooter">
                <a class="underlineHover" href="<?php echo BASE_URL ?>login">Login Page</a>
            </div>
        </div>
    </div>
</body>
<script>
    $(document).ready(function() {
        $("#btn-register").click(function() {

            $('#register-form').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: 'Fill in the name field.',

                    },
                    email: {
                        required: 'Enter your email',
                        email: 'Oops, enter a valid email'
                    },
                    password: {
                        required: 'Password is required',
                        minlength: 'At least 5 characters'
                    }
                },
                submitHandler: function(form) {
                    var data = $("#register-form").serialize();
                    password = $("#password").val();
                    password2 = $("#password2").val();
                    if (password != password2) {
                        $("#recover-alert").addClass('alert-danger');
                        $("#recover-alert").css('display', 'block')
                        $("#message").html('<strong>Different passwords! Please type equal passwords.</strong>');
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo BASE_URL ?>login/register',
                            data: data,
                            dataType: 'json',
                            beforeSend: function() {
                                $("#btn-register").prop('value', 'Registering...');
                            },
                            success: function(response) {
                                if (response.status == "1") {
                                    $("#register-alert").addClass('alert-success');
                                    $("#register-alert").css('display', 'block')
                                    $("#message").html('<strong>Registered.</strong>');
                                    $("#btn-register").prop('value', 'Going to login page...');
                                    localStorage.setItem('email', response.email)
                                    setTimeout(function() {
                                        window.location.href = "<?php echo BASE_URL ?>login";
                                    }, 5000);
                                } else {
                                    $("#register-alert").addClass('alert-danger');
                                    $("#register-alert").css('display', 'block')
                                    $("#message").html('<strong>An error was encountered.</strong>');

                                }

                            }
                        });
                    }
                }
            })
        });
    });
    $('input').attr('autocomplete', 'off');

    var password = $('#password');
    var eye = $("#eye");

    eye.mousedown(function() {
        password.attr("type", "text");
        eye.addClass('loginblue');
    });

    eye.mouseup(function() {
        password.attr("type", "password");
        eye.removeClass('loginblue');
    });


    // To avoid the problem of dragging the image and the password remains exposed
    $("#eye").mouseout(function() {
        $("#password").attr("type", "password");
    });

    var password2 = $('#password2');
    var eye2 = $("#eye2");

    eye2.mousedown(function() {
        password2.attr("type", "text");
        eye2.addClass('loginblue');
    });

    eye2.mouseup(function() {
        password2.attr("type", "password");
        eye2.removeClass('loginblue');
    });

    // To avoid the problem of dragging the image and the password remains exposed
    $("#eye2").mouseout(function() {
        $("#password2").attr("type", "password");
    });
</script>