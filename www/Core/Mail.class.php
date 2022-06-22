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

    self::setCommunConfig($mail, $to, $subject, $body);

    try{
     
      $mail->isSMTP();

      self::setMailConfig( true, $mail);

      /*
      // Usage of mail.trap.io to do some test
      $mail->isSMTP();
      $mail->Host = 'smtp.mailtrap.io';
      $mail->SMTPAuth = true;
      $mail->Port = 2525;
      $mail->Username = '6f9ee7c7e727d6'; // you need to relog to get a new token
      $mail->Password = '7e313959bb4777';
      */
      echo "Message have been send";

    }catch(Exception $e) {
        dd($e);
      
      echo "Message could not be sent.";
      self::setMailConfig( false, $mail);
    }


  }

  public function setMailConfig($gmail, $mail){
       if($gmail) {
          $mail->Host = 'smtp.gmail.com';
          $mail->SMTPAuth = true;
          $mail->SMTPSecure = "tls";
          $mail->Ports = 587;
          $mail->Username = SMTP_USERNAME;
          $mail->Password = SMTP_PWD;
      }else {
        $mail->Host = 'smtp-broadcasts.postmarkapp.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Ports = 587;
        $mail->Username = 'cd8cfbdd-f767-47ca-ae1f-3145f2c9a218';
        $mail->Password = 'cd8cfbdd-f767-47ca-ae1f-3145f2c9a218';
        }

      $mail->Send();
      $mail->smtpClose();
    }
  
  private function setCommunConfig(&$mail, $to, $subject, $body) {
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







