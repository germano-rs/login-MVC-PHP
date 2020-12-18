<?php
define('BASE_URL', '/mvcLogin/'); //change the project name here
$dbname = 'db_mvc';
$host = 'localhost';
$user = 'root';
$password = '';


class Connection
{
    private static $instance;
    private function __construct()
    {
    }
    public static function getConnection()
    {
        if (!isset(self::$instance)) {
            $dbname = 'db_mvc';
            $host = 'localhost';
            $user = 'root';
            $password = '';

            try {
                self::$instance = new PDO('mysql:dbname=' . $dbname . ';host=' . $host, $user, $password);
            } catch (Exception $e) {
                echo 'Error: ' . $e;
            }
        }
        return self::$instance;
    }
}
