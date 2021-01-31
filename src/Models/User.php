<?php

namespace App\Models;

/**
 * Модель пользователей
 */
class User extends BaseModel
{
    /**
     * Минимальная длина пароля
     */
    public const PASSWORD_MIN = 8;

    /**
     * Максимальная длина пароля
     */
    public const PASSWORD_MAX = 20;

    protected $table = 'users';

    /**
     * Проверят пользователя в момент входа в приложение
     * @param array $formData - данные формы
     * @return array
     */
    public function verify(array $formData)
    {
        $data = $this->findItemByLogin($formData['login']);

        if (!empty($data)) {
            if (password_verify($formData['password'], $data['pass'])) {
                unset($data['pass']);
                $data['isActive'] = true;

                return $data;
            }
        }

        return [];
    }

    /**
     * Изменяет пароль
     * @param array $formData - данные формы
     */
    public function changePassword(array $formData)
    {
        $newPassword = password_hash($formData['new_password'], PASSWORD_DEFAULT);

        $sql = 'UPDATE ' . $this->table . ' SET pass = :pass, pass_status = 1 WHERE user_id = :id';

        $this->execQuery($sql, ['id' => $formData['user_id'], 'pass' => $newPassword]);
    }

    /**
     * Возвращает пользователя по логину
     * @param string $login - логин пользователя
     * @return array
     */
    public function findItemByLogin(string $login)
    {
        $sql = 'SELECT ' . $this->table . '.*, accesses.user_role AS access_role FROM ' . $this->table . ' '
            . 'LEFT JOIN accesses ON (' . $this->table . '.access_id = accesses.access_id) '
            . 'WHERE ' . $this->table . '.user_login = :login';

        $result = $this->execQuery($sql, ['login' => $login]);

        return $result->fetch();
    }

    /**
     * Возвращает пользователя по id
     * @param mixed $id - id пользователя
     * @return array
     */
    public function findItemById($id)
    {
        $sql = 'SELECT ' . $this->table . '.*, accesses.user_role AS access_role FROM ' . $this->table . ' '
            . 'LEFT JOIN accesses ON (' . $this->table . '.access_id = accesses.access_id) '
            . 'WHERE ' . $this->table . '.user_id = :id';

        $result = $this->execQuery($sql, ['id' => $id]);

        return $result->fetch();
    }
}
