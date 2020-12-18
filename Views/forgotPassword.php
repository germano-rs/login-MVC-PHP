<body>
    <div class="wrapper fadeInDown">
        <div id="formContent">
            <!-- Tabs Titles -->

            <!-- Icon -->
            <div class="fadeIn first mt-3 mb-4">
                <img src="<?php echo BASE_URL ?>/Public/img/login.svg" id=" icon" width="100" height="100" alt="User Icon" />
            </div>

            <!-- Login Form -->
            <div id="forgot-alert" style="display:none" class="alert  col-sm-12">
                <span class="glyphicon glyphicon-exclamation-sign"></span>
                <span id="message"></span>
            </div>
            <form id="forgot-form">
                <div>
                    <input type="text" id="email" autocomplete="false" class="fadeIn second" name="email" autocorrect="off" spellcheck="false" readonly onfocus="this.removeAttribute ('readonly');">
                    <label class="col-12" for="exampleInputEmail1">E-mail address</label>
                </div>
                <input id="btn-confirm" type="button" class="fadeIn fourth" value="Confirm">
            </form>
        </div>
    </div>
</body>

<script>
    $('document').ready(function() {
        $("#btn-confirm").click(function() {


            var data = $("#forgot-form").serialize();

            $.ajax({
                type: 'POST',
                url: '<?php echo BASE_URL ?>login/forgotPassword',
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    $("#btn-confirm").prop("disabled", true);
                    $("#btn-confirm").prop('value', 'Processing...');
                },
                success: function(response) {
                    if (response.code == "1") {
                        $("#forgot-alert").removeClass('alert-danger');
                        $("#forgot-alert").addClass('alert-success');
                        $("#forgot-alert").css('display', 'block')
                        $("#message").html('<strong>Success! </strong>' + response.message);
                        $("#btn-confirm").prop('value', 'Email Sent');
                        setTimeout(function() {
                            $("#btn-confirm").prop("disabled", false);
                            $("#btn-confirm").prop('value', 'Confirm');
                        }, 5000);
                    } else {
                        $("#forgot-alert").removeClass('alert-success');
                        $("#forgot-alert").addClass('alert-danger');
                        $("#forgot-alert").css('display', 'block')
                        $("#message").html('<strong>Error! </strong>' + response.message);
                        $("#btn-confirm").prop('value', 'Error');
                        setTimeout(function() {
                            $("#btn-confirm").prop("disabled", false);
                            $("#btn-confirm").prop('value', 'Confirm');
                        }, 5000);
                    }
                }
            });
        });

        $('input').attr('autocomplete', 'off');
    });
</script>