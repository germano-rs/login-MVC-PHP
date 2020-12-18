<?php
include_once('../Helper/checkSession.php');
checkSession('logged', 'login'); ?>

This is the Home page.<br>

<a class="underlineHover" href="<?php echo BASE_URL ?>login/logout">Logout</a>

<script>
    $('document').ready(function() {
        name = localStorage.getItem('name')
        if (name) {
            text = 'Welcome,' + name
            speechSynthesis.speak(new SpeechSynthesisUtterance(text))
            name = localStorage.removeItem('name')
        }
        name = localStorage.removeItem('email')

    })
</script>