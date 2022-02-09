<?php

namespace App\Core;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Execption;
use PHPMailer\PHPMailer\SMTP;


require '/var/www/html/libs/PHPMailer/src/PHPMailer.php';
require '/var/www/html/libs/PHPMailer/src/SMTP.php';
require '/var/www/html/libs/PHPMailer/src/Exception.php';


class Mail
{
  private $mail;

  public static function sendMail($to){
      
      $mail = new PHPMailer(true);
      try{
        $mail->isSMTP();
        $mail->SMTPDebug = 4;        
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Ports = 587;
        $mail->Username = SMTP_USERNAME; //'vgcreator1@gmail.com'; 
        $mail->Password = SMTP_PWD; //'ESGI2021'; 
       /*
        * Usage of mail.trap.io to do some test
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'e23cce7384579d';
        $mail->Password = '376a0f4a43e568';
        */

        $mail->Subject = "TEst de email with phpmailer";
        $mail->setFrom('vgcreator1@gmail.com');
        $mail->Body = "this is a plain test";
        $mail->addAddress($to);
        $mail->Send();
        $mail->smtpClose();
        echo "Message have been send";

      }catch(Exception $e) {
        echo "Message could not be sent.";
      }

   
  }

    /*
    public function __construct(){
        
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = "stmp.google.com";
        $mail->SMTPAuth = "true";
        $mail->SMTPSecure = "tls";
        $mail->Ports = "587";
        $mail->Username = "vgcreator1@gmail.com";

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
    */          
}







