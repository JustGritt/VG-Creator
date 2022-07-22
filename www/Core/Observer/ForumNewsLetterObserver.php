<?php

namespace App\Core\Observer;
use App\Core\Mail;

class ForumNewsLetterObserver implements ObsInterface{

    public $email;


    public function update(Newsletter $newsletter, string $event,  ...$args)
    {
        switch($event){
            case Newsletter::EVENT_NEW_EVENT:
                $this->newEventFired($newsletter, $args);
                break;
            case Newsletter::EVENT_NEW_SUBSCRIBE:
                $this->newSubscriber($newsletter, $args);
                break;
        }
    }

    public function newEventFired(Newsletter $newsletter, $args){
        $this->email = $args[0][0]; 
        var_dump($this->email);
    }

    public function newSubscriber(Newsletter $newsletter, $args){
       // sendP
       $email = $args[0][0]; 
       $mail = new Mail();
       $subject = "Vous etes bien inscrit à la newsletter";
       $body ="Bien bon";
       $mail->sendMail($email, $body, $subject);
        var_dump($newsletter);
       // echo "New subscriber".$newsletter;
    }
}