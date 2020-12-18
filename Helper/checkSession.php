<?php
session_start();

function checkSession($session, $redirect)
{
    if (!isset($_SESSION[$session])) :
        header("Location: $redirect");
        exit;
    endif;
}
