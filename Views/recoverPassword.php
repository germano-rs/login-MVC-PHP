<body>

    <div class="wrapper fadeInDown">
        <div id="formContent">

            <!-- Icon -->
            <div class="fadeIn first mt-3 mb-4">
                <img src="<?php echo BASE_URL ?>/Public/img/login.svg" id=" icon" width="100" height="100" alt="User Icon" />
            </div>

            <!-- Login Form -->
            <div id="recover-alert" style="display:none" class="alert col-sm-12">
                <span class="glyphicon glyphicon-exclamation-sign"></span>
                <span id="message"></span>
            </div>


            <form id="recover-form">
                <div>
                    <input type="hidden" id="token" name="token" value="<?php echo $data['token']; ?>">
                    <input class="input input-group" required type="password" id="password" class="fadeIn third" name="password" autocorrect="off" spellcheck="false" readonly onfocus="this.removeAttribute ('readonly');">
                    <label class="col-12" id="eye" for="password">Password <img id="svgeye" src="<?php echo BASE_URL ?>/Public/img/eye.svg" width="20" height="20" class="mb-1" alt="User Icon" /></label>
                    <input class="input input-group" required type="password" id="password2" class="fadeIn third" name="password2" autocorrect="off" spellcheck="false" readonly onfocus="this.removeAttribute ('readonly');">
                    <label class="col-12" id="eye2" for="password">Confirm Password <img id="svgeye" src="<?php echo BASE_URL ?>/Public/img/eye.svg" width="20" height="20" class="mb-1" alt="User Icon" /></label>
                </div>
                <input id="btn-recover" type="submit" class="fadeIn fourth" value="Renew Password">
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
        $("#btn-recover").click(function(e) {

            var data = $("#recover-form").serialize();
            ipassword = $("#password").val();
            ipassword2 = $("#password2").val();

            if (ipassword != ipassword2) {
                e.preventDefault();
                $("#recover-alert").addClass('alert-danger');
                $("#recover-alert").css('display', 'block')
                $("#message").html('<strong>Different passwords! Please type equal passwords.</strong>');
            } else {
                $('#recover-form').validate({
                    rules: {
                        password: {
                            required: true,
                            minlength: 5
                        },
                        password2: {
                            required: true,
                            minlength: 5
                        }

                    },
                    messages: {
                        password: {
                            required: 'Password is required.',
                            minlength: 'At least 5 characters'
                        },
                        password2: {
                            required: 'Enter the same password entered previously.',
                            minlength: 'At least 5 characters'
                        }
                    },
                    submitHandler: function(form) {

                        $.ajax({

                            type: 'POST',
                            url: '<?php echo BASE_URL ?>login/renewPassword',
                            data: data,
                            dataType: 'json',
                            beforeSend: function() {

                                $("#btn-recover").prop('value', 'Validating...');
                            },
                            success: function(response) {
                                if (response.status == "1") {
                                    $("#btn-recover").prop('value', 'Going to login page...');
                                    $("#recover-alert").removeClass('alert-danger');
                                    $("#recover-alert").addClass('alert-success');
                                    $("#recover-alert").css('display', 'block')
                                    $("#message").html('<strong>Success! </strong>' + response.message);
                                    setTimeout(function() {
                                        window.location.href = "<?php echo BASE_URL ?>login";
                                    }, 5000);
                                } else {
                                    $("#recover-alert").removeClass('alert-success');
                                    $("#recover-alert").addClass('alert-danger');
                                    $("#recover-alert").css('display', 'block')
                                    $("#message").html('<strong>Error! </strong>' + response.message);
                                    $("#btn-recover").prop('value', 'Renew Password');


                                }
                            }
                        });
                    }
                });
            }

        });

        $('input').attr('autocomplete', 'off');

        var password = $('#password');
        var eye = $("#eye");

        eye.mousedown(function() {
            password.attr("type", "text");
            eye.addClass('loginblue'); - +3, .96
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
    });
</script>