<?php
include_once '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
include_once '../vendor/phpmailer/phpmailer/src/SMTP.php';

$mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->IsSMTP(); // enable SMTP
// Configurações do servidor
$mail->isSMTP();        //Devine o uso de SMTP no envio
$mail->SMTPAuth = true; //Habilita a autenticação SMTP
$mail->Username = 'youremail@email.com';
$mail->Password = 'youremailpassword';
// Criptografia do envio SSL também é aceito
$mail->SMTPSecure = 'tls';
// Informações específicadas pelo Google
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
// Define o remetente
$mail->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true));
// Você pode habilitar esta opção caso tenha problemas. Assim pode identificar mensagens de erro. 
// $mail->SMTPDebug = 2; 
// Define o remetente 
// Seu e-mail 

$mail->From = "youremail@email.com";
// Seu nome 
$mail->FromName = "yourname";
$mail->CharSet = 'UTF-8';
$mail->isHTML(true);
