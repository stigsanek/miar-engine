<?php

namespace App\Models;

use App\Components\Session;

/**
 * Модель пользователей
 */
class User extends BaseModel
{
    /**
     * Имя таблицы в БД
     */
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

                Session::setAlert('primary', 'Добро пожаловать, ' . $data['first_name'] . '!');

                if ($data['pass_status'] == '0') {
                    Session::setAlert(
                        'warning',
                        'В целях безопасности, вам необходимо изменить пароль. '
                            . 'Пожалуйста, пройдите по <b><a href="/profile/security">ссылке</a></b>.'
                    );
                }

                return $data;
            }
        }

        $this->isError = true;
        Session::setAlert('danger', 'Ошибка: неправильно указан логин или пароль');
    }

    /**
     * Изменяет пароль
     * @param array $formData - данные формы
     */
    public function changePassword(array $formData)
    {
        $user = $this->findItemById($formData['user_id']);

        if (password_verify($formData['cur_password'], $user['pass'])) {

            if ($formData['cur_password'] === $formData['new_password']) {
                $this->isError = true;
                return Session::setAlert('danger', 'Ошибка: новый пароль не должен совпадать с текущим');
            }

            $newPassword = password_hash($formData['new_password'], PASSWORD_DEFAULT);

            $sql = 'UPDATE ' . $this->table . ' SET pass = :pass, pass_status = 1 WHERE user_id = :id';

            $this->prepQuery = $this->db->prepare($sql);
            $response = $this->prepQuery->execute(['id' => $formData['user_id'], 'pass' => $newPassword]);

            $this->setQueryState($response);
        } else {
            $this->isError = true;
            Session::setAlert('danger', 'Ошибка: неправильно указан текущий пароль');
        }
    }

    /**
     * Возвращает пользователя по логину
     * @param string $login - логин пользователя
     * @return array
     */
    private function findItemByLogin(string $login)
    {
        $sql = 'SELECT ' . $this->table . '.*, accesses.user_role AS access_role FROM ' . $this->table . ' '
            . 'LEFT JOIN accesses ON (' . $this->table . '.access_id = accesses.access_id) '
            . 'WHERE ' . $this->table . '.user_login = :login';

        $this->prepQuery = $this->db->prepare($sql);
        $this->prepQuery->execute(['login' => $login]);

        return $this->prepQuery->fetch();
    }

    /**
     * Возвращает пользователя по id
     * @param mixed $id - id пользователя
     * @return array
     */
    private function findItemById($id)
    {
        $sql = 'SELECT ' . $this->table . '.*, accesses.user_role AS access_role FROM ' . $this->table . ' '
            . 'LEFT JOIN accesses ON (' . $this->table . '.access_id = accesses.access_id) '
            . 'WHERE ' . $this->table . '.user_id = :id';

        $this->prepQuery = $this->db->prepare($sql);
        $this->prepQuery->execute(['id' => $id]);

        return $this->prepQuery->fetch();
    }
}
