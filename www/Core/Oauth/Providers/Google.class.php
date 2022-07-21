<?php

namespace App\Core\Oauth\Providers;

use App\Core\Oauth\ProviderInterface;

class Google extends Provider implements ProviderInterface
{
    protected $client_id;
    protected $client_secret;
    protected $redirect_uri;

    public function __construct( $client_id, $client_secret, $redirect_uri)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
    }

    public static function getBaseAuthorizationUrl()
    {
        return "https://accounts.google.com/o/oauth2/v2/auth?";
    }

    //TODO: Implement getBaseAccessTokenUrl()
    public static function getBaseAccessTokenUrl()
    {
        return "https://www.googleapis.com/oauth2/v4/token?";
    }

    public static function getBaseMeUrl()
    {
        return "https://www.googleapis.com/oauth2/v2/userinfo?";
    }


    public static function getName()
    {
        return "Google";
    }

    public static function getState()
    {
        return "Google";
    }

    public static function getScope()
    {
        return implode(" ", [
            "https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email"
        ]);
    }

}
