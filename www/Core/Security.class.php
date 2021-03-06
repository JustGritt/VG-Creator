<?php

namespace App\Core;


class Security
{

    //* genrerate csrf token
    public static function generateCsfrToken(): string
    {
        $token = md5(uniqid(rand(), true));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    public static function checkCsrfToken($token): bool
    {
        if (isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] == $token) {
            return true;
        }
        return false;
    }

    public static function isLoggedIn(): bool
    {
        if (isset($_SESSION['id'])) {
            return true;
        }
        return false;
    }

    public static function isMember(): bool
    {
        if (isset($_SESSION['id']) && ($_SESSION['VGCREATOR'] == IS_MEMBER) ?? '') {
            return true;
        }
        return false;
    }

    public static function isVGdmin(){
        if (isset($_SESSION['VGCREATOR']) && ($_SESSION['VGCREATOR'] == IS_ADMIN) ?? '') {
            return true;
        }
        return false;
    }

    public static function isAdmin(): bool
    {
        if (isset($_SESSION['id']) && isset($_SESSION['role']) && ($_SESSION['role'] == 'Admin') ?? '') {
            return true;
        }
        return false;
    }

    public static function isGuest(): bool
    {
        if (isset($_SESSION['id']) && isset($_SESSION['role']) && ($_SESSION['role'] == 'Guest') ?? '') {
            return true;
        }
        return false;
    }

    public static function isModerator(): bool
    {
        if (isset($_SESSION['id']) && isset($_SESSION['role']) &&  ($_SESSION['role'] ==  'Moderator')) {
            return true;
        }
        return false;
    }

    public static function isEditor(): bool
    {
        if (isset($_SESSION['id']) && isset($_SESSION['role']) &&  ($_SESSION['role'] == 'Editor')) {
            return true;
        }
        return false;
    }

}
