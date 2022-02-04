<?php

require 'includes/PHPMailer.php';
require 'includes/SMTP.php';
require 'includes/Exeception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


$mail = new PHPMailer();

$mail->isSMTP();
$mail->Host = "stmp.google.com";
$mail->SMTPAuth = "true";
$mail->SMTPSecure = "tls";
$mail->Ports = "587";
$mail->Username = "vgcreator1@gmail.com";
$mail->Password = "ESGI2021!";
$mail->Subject = "TEst de email with phpmailer";
$mail->setForm("charles@gmail.com");
$mail->Body = "this is a plain test";

$mail->addAddress("charles@gmail.com");

if($mail->Send()) {
    echo "mail send";
};
else {
    "Error phpmailer";
}

$mail->stmpClose();
