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

        // $mail->isSMTP();
        // $mail->Host = 'smtp-broadcasts.postmarkapp.com';
        // $mail->SMTPDebug = 4;// PREPROD ONLY VERBOSE DEBUG
        // $mail->SMTPAuth = true;
        // $mail->SMTPSecure = "tls";
        // $mail->Ports = 587;
        // $mail->Username = SMTP_USERNAME;
        // $mail->Password = SMTP_PWD;

        // $mail->isSMTP();
        // $mail->Host = 'smtp.mailtrap.io';
        // $mail->SMTPAuth = true;
        // $mail->Port = 2525;
        // $mail->Username = 'e23cce7384579d';
        // $mail->Password = '376a0f4a43e568';

        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '84aad762c7fb11';
        $mail->Password = 'a20617c616a704';

        $mail->isSMTP();
        $mail->Host = 'smtp-broadcasts.postmarkapp.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Ports = 587;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PWD;
        $mail->CharSet = 'utf-8';
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->setFrom('do-not-reply@vgcreator.fr');
        $mail->setFromName = 'VG-CREATOR';
        $mail->addCustomHeader($to);
        $mail->Body = $body; ;
        $mail->addAddress($to);
        $mail->Send();
        $mail->smtpClose();
    }catch(Exception $e) {
      echo "Message could not be sent.";
    }
  }

}







