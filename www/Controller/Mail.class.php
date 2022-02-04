<?php

require 'includes/PHPMailer.php';
require 'includes/SMTP.php';
require 'includes/Exeception.php';

use App\Controller\PHPMailer;
use App\Controller\SMTP;
use App\Controller\Exception;

class Mail(){
    
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
        $mail->setForm("vgcreator1@gmail.com");
        $mail->Body = "this is a plain test";
        $mail->addAddress("vgcreator1@gmail.com");
        $mail->$this->isMailSend();
        $mail->stmpClose();
    }

    public function isMailSend(){
        if($mail->Send()) {
            echo "mail send";
        };
        else {
            "Error phpmailer";
            die();
        }

    }
    
}







