<?php

namespace App\Core\Observer;

class Newsletter
{
    const EVENT_NEW_SUBSCRIBE = 'new_subscribe';
    const EVENT_NEW_EVENT = 'new_event';

    public static $instance = null;

    public static function getInstance()
    {
        if(is_null(self::$instance))
        {self::$instance = new Newsletter();}
        return self::$instance;
    }

    /** 
    *  @var ObsInterface[]
    */
    private $observers = [];

    function subscribe(string $email)
    {
        $this->notify(self::EVENT_NEW_SUBSCRIBE, $email);
    }

    function newEvent($args)
    {
        $this->notify(self::EVENT_NEW_EVENT, $args);
    }


    function attach(ObsInterface $observer)
    {
        $this->observers[spl_object_hash($observer)]= $observer;
    }

    function detach(ObsInterface $observer)
    {
        unset($this->observers[spl_object_hash($observer)]);
    }

    function notify(string $event, ...$args)
    {
        var_dump("update all observer", $this->observers);
        foreach ($this->observers as $observer) {
            $observer->update($this, $event, $args);
        }
    }
}
