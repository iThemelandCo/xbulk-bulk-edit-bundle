<?php

namespace wcbef\classes\helpers;

class Session
{
    public static function set($session, $value)
    {
        $_SESSION[sanitize_text_field($session)] = $value;
    }

    public static function get($session)
    {
        return (isset($_SESSION[$session])) ? Sanitizer::array($_SESSION[$session]) : null;
    }

    public static function has($session)
    {
        return isset($_SESSION[sanitize_text_field($session)]);
    }

    public static function get_flush($session)
    {
        if (self::has($session)) {
            $session_value = $_SESSION[sanitize_text_field($session)];
            unset($_SESSION[$session]);
            return $session_value;
        }
        return null;
    }
}
