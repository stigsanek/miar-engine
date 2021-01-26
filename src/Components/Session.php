<?php

namespace App\Components;

/**
 * Компонент взаимодействия с сессией
 */
class Session
{
    /**
     * Проверяет аутентификацию пользователя
     * @return bool
     */
    public function isAuthUser()
    {
        if (isset($_SESSION['user_id'])) {
            return $_SESSION['user_ip'] === $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * Логинит пользователя
     * @param array $userData - данные о пользователе из БД
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
     * Разлогинивает пользователя
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
     * Возвращает id пользователя
     * @return string
     */
    public function getUserId()
    {
        return $_SESSION['user_id'];
    }

    /**
     * Возвращает логин пользователя
     * @return string
     */
    public function getUserLogin()
    {
        return $_SESSION['user_login'];
    }

    /**
     * Возвращает полное имя пользователя
     * @return string
     */
    public function getUserFullName()
    {
        return $_SESSION['last_name'] . ' ' . $_SESSION['first_name'];
    }

    /**
     * Проверяет роль admin
     * @return bool
     */
    public function isAdmin()
    {
        return $this->getAccess() === 'admin';
    }

    /**
     * Проверяет роль user
     * @return bool
     */
    public function isUser()
    {
        return $this->getAccess() === 'user';
    }

    /**
     * Проверяет роль guest
     * @return bool
     */
    public function isGuest()
    {
        return $this->getAccess() === 'guest';
    }

    /**
     * Возвращает роль пользователя
     * @return string
     */
    public function getAccess()
    {
        if (isset($_SESSION['access_role'])) {
            return $_SESSION['access_role'];
        }
    }

    /**
     * Записывает данные в сессию
     * @param string $value - имя переменной для сессии
     * @param mixed $data - данные
     */
    public function setData(string $value, $data)
    {
        $_SESSION[$value] = $data;
        $_SESSION['variables'][] = $value;
    }

    /**
     * Возвращает данные из сессии
     * @param string $value - имя переменной в сессии
     * @return mixed
     */
    public function getData(string $value)
    {
        if (isset($_SESSION[$value])) {
            return $_SESSION[$value];
        }
    }

    /**
     * Удаляет данные из сессии
     * @param string $value - имя переменной в сессии
     */
    public function resetData(string $value)
    {
        if (isset($_SESSION[$value])) {
            $_SESSION[$value] = null;
        }
    }

    /**
     * Записывает уведомление в сессию
     * @param string $type - имя css-класса уведомлений
     * @param string $message - сообщение
     */
    public static function setAlert(string $type, string $message)
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
        $_SESSION['alerts'][] = $_SESSION['flash'];

        unset($_SESSION['flash']);
    }

    /**
     * Возвращает уведомления из сессии
     * @return array
     */
    public static function getAlert()
    {
        if (isset($_SESSION['alerts'])) {
            $alerts = $_SESSION['alerts'];
            unset($_SESSION['alerts']);

            return $alerts;
        }
    }
}
