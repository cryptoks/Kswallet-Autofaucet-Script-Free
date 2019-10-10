<?php
/**
 *  Class for AUTH of user log in
 */
class AUTH
{
    //Check if the user is logged in
    public function LoginAUTH($session, $redirect)
    {
        // If session variable is not set it will redirect to login page
        if (!isset($_SESSION[$session]) || empty($_SESSION[$session])) {
            header("location: $redirect");
            exit;
        }
    }

    //Log out the user
    public function Logout()
    {
        // Unset all of the session variables
        $_SESSION = array();
        // Destroy the session.
        session_destroy();
    }

    //Check if session expired
    public function SessionExpired($time, $location)
    {
        //Checking if session ended
        if ($time > $_SESSION['expire']) {
            session_destroy();
            header("location: $location");
        }
    }
}


/**
 * Class for validating variables and encoding decoding variables
 */
class ValidateVariable
{

    public function CleanString($variable)
    {
        $string = htmlspecialchars(stripslashes($variable));
        return $string;
    }

}
/**
 * Create a random Hash with give length
 */
class Hash
{
    public function Random($length = false)
    {
        if (empty($length)) {
            $length = "6";
        }
        $str = "";
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;

    }
}



