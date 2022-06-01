<?php

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Execption;
use PHPMailer\PHPMailer\SMTP;


require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';
require 'libs/PHPMailer/src/Exception.php';


class Mail
{
  private $mail;

  public static function sendMail($to, $body, $subject){

    $mail = new PHPMailer(true);
    try{
     
      $mail->isSMTP();
      //$mail->SMTPDebug = 4; PREPROD ONLY VERBOSE DEBUG
            
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->SMTPSecure = "tls";
      $mail->Ports = 587;
      $mail->Username = SMTP_USERNAME;
      $mail->Password = SMTP_PWD;
      /*
      $mail->Host = 'smtp-broadcasts.postmarkapp.com';
      $mail->SMTPAuth = true;
      $mail->SMTPSecure = "tls";
      $mail->Ports = 587;
      $mail->Username = SMTP_USERNAME;
      $mail->Password = SMTP_PWD;
      
      
      // Usage of mail.trap.io to do some test
      $mail->isSMTP();
      $mail->Host = 'smtp.mailtrap.io';
      $mail->SMTPAuth = true;
      $mail->Port = 2525;
      $mail->Username = '6f9ee7c7e727d6'; // you need to relog to get a new token
      $mail->Password = '7e313959bb4777';
      */

      $this->setCommumConfig($mail, $to, $subject);
      $mail->Send();
      $mail->smtpClose();
      echo "Message have been send";

    }catch(Exception $e) {
      
      echo "Message could not be sent.";
    }


  }
  
  private function setCommunConfig(&$mail, $to, $subject) {
    $mail->CharSet = 'utf-8';
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->setFrom('do-not-reply@vgcreator.fr');
    $mail->setFromName = 'VG-CREATOR';
    $mail->addCustomHeader($to);
    $mail->Body = $body; //"http://localhost/register/confirmationmail.php?id=".$_SESSION['id']."&cles=".$cles;
    $mail->addAddress($to);
  }

}







