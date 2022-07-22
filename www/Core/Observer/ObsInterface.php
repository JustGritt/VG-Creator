<?php
    
namespace App\Core\Observer;

interface ObsInterface{
    function update(Newsletter $newsletter, string $event, ...$args);
}