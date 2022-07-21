<?php

namespace App\Core\Oauth\Providers;

use App\Core\Oauth\ProviderInterface;
use Stripe\Radar\ValueList;

abstract class Provider implements ProviderInterface{


    abstract static function getState();
    abstract static function getName();
    abstract static function getScope();
    abstract static function getBaseAuthorizationUrl();
    abstract static function getBaseMeUrl();
    //abstract static function getRedirectUri();

    public function getclientId() {
        return $this->client_id;
    }

    public function getClientSecret() {
        return $this->client_secret;
    }

    public function getAuthorizationUrl()
    {
        $queryParams= http_build_query([
            'client_id' => $this->getClientId(),
            'redirect_uri' => $this->redirect_uri,
            'response_type' => 'code',
            'scope' => $this->getScope(),
            // "state" => bin2hex(random_bytes(16))
            'state' => $this->getState()
        ]);
        $link = $this->getBaseAuthorizationUrl() . $queryParams;
        return $link;
    }

    public function toString($user){

        if (isset($_GET['state'])) {
            $state = $_GET['state'];
        } else {
            $state = "";
        }
        switch ($state) {
            case 'Google':
                $name = ucfirst($user['given_name']);
                $surname = ucfirst($user['family_name']);
                echo "Hello {$name} {$surname}";
                break;
            case 'Spotify':
                $user = explode(" ", $user['display_name']);
                $name = ucfirst($user[0]);
                $surname = ucfirst($user[1]);
                echo "Hello {$name} {$surname}";
                break;
            case 'Discord':
                $surname = ucfirst($user['username']);
                echo "Hello {$surname}";
                break;
            default:
                echo "Hello {$user['lastname']} {$user['firstname']}";
                break;
        }

    }

    public function GetAccessToken() {
        /*
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            ["username" => $username, "password" => $password] = $_POST;
            $specifParams = [
                'username' => $username,
                'password' => $password,
                'grant_type' => 'password',
            ];
            $queryParams = http_build_query(array_merge([
                'client_id' => $this->getclientId(), //'621f59c71bc35',
                'client_secret' => $this->getClientSecret(),//'621f59c71bc36',
                'state' => 'simple',
                'redirect_uri' => 'http://localhost:8081/callback',
            ], $specifParams));
            $response = @file_get_contents("http://server:8080/token?{$queryParams}");
            if ($response === false) {
                http_response_code(404);
                exit;
            }
            $token = json_decode($response, true);
            return $token;
        }*/

        ["code" => $code, "state" => $state] = $_GET;
        $url = $this->getBaseAccessTokenUrl();
        $redirect_uri = "http://localhost/login";
        $curl = 'client_id=' . $this->getclientId() . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $this->getClientSecret() . '&code='. $code . '&grant_type=authorization_code';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curl);
        $data = json_decode(curl_exec($ch), true);
        var_dump($data);
        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);

        if ( !isset($data['access_token']) && $http_code != 200) {
            throw new \RuntimeException('Error: cannot get access token for user');
            exit;
        }

        return $data;
    }

    public function validateToken($token)
    {

        $access_token = $token['access_token'];
        $context = stream_context_create([
            'http' => [
                'header' => "Authorization: Bearer {$access_token}"
            ]
        ]);
        $response = file_get_contents($this->getBaseMeUrl(), false, $context);
        $user = json_decode($response, true);

        return $user;
    }


}
