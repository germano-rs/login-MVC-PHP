<?php
require '../Core/Config.php';


spl_autoload_register(function ($filename) {
    if (file_exists('../Controllers/' . $filename . '.php')) {
        require '../Controllers/' . $filename . '.php';
    } elseif (file_exists('../Core/' . $filename . '.php')) {
        require '../Core/' . $filename . '.php';
    } elseif (file_exists('../Models/' . $filename . '.php')) {
        require '../Models/' . $filename . '.php';
    }
});
