<?php
session_start();
session_destroy();
?>

<body>
    <div class="wrapper fadeInDown">
        <div id="formContent">

            <!-- Icon -->
            <div class="fadeIn first mt-3 mb-4">
                <img src="<?php echo BASE_URL ?>/Public/img/login.svg" id=" icon" width="100" height="100" alt="User Icon" />
            </div>

            <!-- Login Form -->
            <div id="login-alert" style="display:none" class="alert alert-danger col-sm-12">
                <span class="glyphicon glyphicon-exclamation-sign"></span>
                <span id="message"></span>
            </div>
            <form id="login-form">
                <div>
                    <input type="text" id="email" autocomplete="false" class="fadeIn second" name="email" autocorrect="off" spellcheck="false" readonly onfocus="this.removeAttribute ('readonly');">
                    <label class="col-12" for="exampleInputEmail1">E-mail address</label>
                    <input class="input input-group" type="password" id="password" class="fadeIn third" name="password" autocorrect="off" spellcheck="false" readonly onfocus="this.removeAttribute ('readonly');">
                    <label class="col-12" id="eye" for="password">Password <img id="svgeye" src="<?php echo BASE_URL ?>/Public/img/eye.svg" width="20" height="20" class="mb-1" alt="User Icon" /></label>
                </div>
                <input id="btn-login" type="button" class="fadeIn fourth" value="Log In">


            </form>

            <!-- Remind Passowrd -->
            <div id="formFooter">
                <a class="underlineHover" href="<?php echo BASE_URL ?>login/forgotPage">Forgot Password?</a>
            </div>
            <!-- Register Passowrd -->
            <div id="formFooter">
                <a id="register" class="underlineHover" href="<?php echo BASE_URL ?>login/registerPage">Register Now</a> </div>
        </div>
    </div>
</body>
<script>
    $('document').ready(function() {
        emailLocal = localStorage.getItem('email')
        if (emailLocal != '') {
            $("#email").prop('value', emailLocal);
        }
        $("#btn-login").click(function() {
            var data = $("#login-form").serialize();

            $.ajax({
                type: 'POST',
                url: '<?php echo BASE_URL ?>login/login',
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    $("#btn-login").html('Validating ...');
                },
                success: function(response) {
                    if (response.code == "1") {
                        $("#login-alert").css('display', 'none')
                        name = localStorage.removeItem('email')
                        localStorage.setItem('name', response.name)
                        window.location.href = "home";
                    } else {
                        $("#login-alert").css('display', 'block')
                        $("#message").html('<strong>Error! </strong>' + response.message);
                    }
                }
            });
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
</script>