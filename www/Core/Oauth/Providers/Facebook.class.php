<?php

namespace App\Core\Oauth\Providers;

use App\Core\Oauth\ProviderInterface;

class Facebook extends Provider implements ProviderInterface
{
    protected $client_id;
    protected $client_secret;
    protected $redirect_uri;

    public function __construct($client_id, $client_secret, $redirect_uri)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
    }

    public static function getBaseAuthorizationUrl()
    {
        return "https://www.facebook.com/v2.10/dialog/oauth?";
    }

    public static function getBaseAccessTokenUrl()
    {
        return "https://graph.facebook.com/v2.10/oauth/access_token?";
    }

    public static function getBaseMeUrl()
    {
        return "https://graph.facebook.com/v2.10/me?";
    }

    public static function getName()
    {
        return "Facebook";
    }

    public static function getState()
    {
        return "Facebook";
    }

    public static function getScope()
    {
        return implode(" ", [
            "public_profile",
            "email"
        ]);
    }
}
