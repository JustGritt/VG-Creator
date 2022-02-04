<?php

namespace App\Core;
/*
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Execption;
use PHPMailer\PHPMailer\SMTP;
*/

require '/var/www/html/libs/PHPMailer/src/PHPMailer.php';
require '/var/www/html/libs/PHPMailer/src/SMTP.php';
require '/var/www/html/libs/PHPMailer/src/Exception.php';


class Mail
{
    private $mail;

    public function __construct(){
        
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = "stmp.google.com";
        $mail->SMTPAuth = "true";
        $mail->SMTPSecure = "tls";
        $mail->Ports = "587";
        $mail->Username = "vgcreator1@gmail.com";
        $mail->Password = "ESGI2021!";

    }
    
    public function sendMail(){
    
        $mail->Subject = "TEst de email with phpmailer";
        $mail->setForm("vgcreator1@gmail.com", "Mailer");
        $mail->Body = "this is a plain test";
        $mail->addAddress("vgcreator1@gmail.com");
        //$mail->$this->isMailSend();
        if($mail->Send()) {
            echo "mail send";
        }
        else {
            "Error phpmailer";
            die();
        }
        $mail->stmpClose();
    }

    public function isMailSend(){
        if($mail->Send()) {
            echo "mail send";
        }
        else {
            "Error phpmailer";
            die();
        }

    }
    
}







