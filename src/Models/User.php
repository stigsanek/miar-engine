<?php

namespace App\Models;

/**
 * User model
 */
class User extends BaseModel
{
    /**
     * Minimum password length
     */
    public const PASSWORD_MIN = 8;

    /**
     * Maximum password length
     */
    public const PASSWORD_MAX = 20;

    protected $table = 'users';

    /**
     * Checks the user at the moment of entering the application
     * @param array $formData - form data
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
     * Changes password
     * @param array $formData - form data
     */
    public function changePassword(array $formData)
    {
        $newPassword = password_hash($formData['new_password'], PASSWORD_DEFAULT);

        $sql = 'UPDATE ' . $this->table . ' SET pass = :pass, pass_status = 1 WHERE user_id = :id';

        $this->execQuery($sql, ['id' => $formData['user_id'], 'pass' => $newPassword]);
    }

    /**
     * Finds a user by login
     * @param string $login - user login
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
     * Finds a user by id
     * @param mixed $id - user id
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
