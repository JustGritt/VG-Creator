<?php

namespace App\Core\Oauth\Providers;

use App\Core\Oauth\ProviderInterface;


class Discord extends Provider implements ProviderInterface
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
        return "https://discord.com/api/oauth2/authorize?";
    }

    //TODO: Implement getBaseAccessTokenUrl()
    public static function getBaseAccessTokenUrl()
    {
        return "https://discord.com/api/oauth2/token?";
    }

    public static function getBaseMeUrl()
    {
        return "https://discord.com/api/users/@me?";
    }


    public static function getName()
    {
        return "Discord";
    }

    public static function getState()
    {
        return "Discord";
    }

    public static function getScope()
    {
        return implode(" ", [
            "email",
            "identify"
        ]);
    }


}