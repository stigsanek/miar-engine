<?php

namespace App\Components;

/**
 * Session component
 */
class Session
{
    /**
     * Checks user authentication
     * @return bool
     */
    public function isAuthUser()
    {
        if (isset($_SESSION['user_id'])) {
            return $_SESSION['user_ip'] === $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * Performs authorization
     * @param array $userData - user data from the database
     */
    public function authenticate(array $userData)
    {
        foreach ($userData as $item => $value) {
            $_SESSION[$item] = $value;
            $_SESSION['variables'][] = $item;
        }

        $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Logs out
     */
    public function logout()
    {
        foreach ($_SESSION['variables'] as $item) {
            unset($_SESSION[$item]);
        }

        unset($_SESSION['user_ip']);
        unset($_SESSION['variables']);
    }

    /**
     * Returns user id
     * @return string
     */
    public function getUserId()
    {
        return $_SESSION['user_id'];
    }

    /**
     * Returns user login
     * @return string
     */
    public function getUserLogin()
    {
        return $_SESSION['user_login'];
    }

    /**
     * Returns user full name
     * @return string
     */
    public function getUserFullName()
    {
        return $_SESSION['last_name'] . ' ' . $_SESSION['first_name'];
    }

    /**
     * Checks "admin" role
     * @return bool
     */
    public function isAdmin()
    {
        return $this->getAccess() === 'admin';
    }

    /**
     * Checks "user" role
     * @return bool
     */
    public function isUser()
    {
        return $this->getAccess() === 'user';
    }

    /**
     * Checks "guest" role
     * @return bool
     */
    public function isGuest()
    {
        return $this->getAccess() === 'guest';
    }

    /**
     * Returns the user's role
     * @return string
     */
    public function getAccess()
    {
        if (isset($_SESSION['access_role'])) {
            return $_SESSION['access_role'];
        }
    }

    /**
     * Writes data to the session
     * @param string $value - variable name
     * @param mixed $data - data
     */
    public function setData(string $value, $data)
    {
        $_SESSION[$value] = $data;
        $_SESSION['variables'][] = $value;
    }

    /**
     * Returns data from the session
     * @param string $value - variable name
     * @return mixed
     */
    public function getData(string $value)
    {
        if (isset($_SESSION[$value])) {
            return $_SESSION[$value];
        }
    }

    /**
     * Removes data from session
     * @param string $value - variable name
     */
    public function resetData(string $value)
    {
        if (isset($_SESSION[$value])) {
            $_SESSION[$value] = null;
        }
    }

    /**
     * Writes a notification to the session
     * @param string $type - css class
     * @param string $message - message
     */
    public function setAlert(string $type, string $message)
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
        $_SESSION['alerts'][] = $_SESSION['flash'];

        unset($_SESSION['flash']);
    }

    /**
     * Returns a notification from the session
     * @return array
     */
    public function getAlert()
    {
        if (isset($_SESSION['alerts'])) {
            $alerts = $_SESSION['alerts'];
            unset($_SESSION['alerts']);

            return $alerts;
        }
    }
}
