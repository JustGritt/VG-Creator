<?php
namespace App\Core\Oauth;

interface ProviderInterface
{
    public static function getName();
    public static function getState();
    public static function getScope();
    public static function getBaseAuthorizationUrl();
    public static function getBaseMeUrl();
    /*
    public function getClientId();
    public function toString($user);
    public function validateToken($token);*/
}