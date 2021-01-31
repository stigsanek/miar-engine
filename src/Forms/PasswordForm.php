<?php

namespace App\Forms;

use App\Models\User;

/**
 * Форма изменения пароля
 */
class PasswordForm extends BaseForm
{
    public $name = 'change';

    public $dataFields = ['user_id', 'cur_password', 'new_password', 'new_password_repeat'];

    protected $rules = [
        ['char', ['new_password', 'new_password_repeat']],
        ['length', ['new_password', 'new_password_repeat']],
        ['repeat', 'new_password_repeat'],
        ['current', 'cur_password'],
        ['required', ['user_id', 'cur_password', 'new_password', 'new_password_repeat']]
    ];

    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->model = new User();
    }

    /**
     * Валидация символов пароля
     * @param array $fields - поля
     */
    protected function runCharValidator(array $fields)
    {
        foreach ($fields as $field) {
            if (!preg_match('/(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])/', $this->formData[$field])) {
                $this->errors[$field] = 'Значение пароля не соответствует необходимым требованиям';
            }
        }
    }

    /**
     * Валидация длины пароля
     * @param array $fields - поля
     */
    protected function runLengthValidator(array $fields)
    {
        foreach ($fields as $field) {
            $length = strlen($this->formData[$field]);

            if (($length > 0 && $length < $this->model::PASSWORD_MIN) || $length > $this->model::PASSWORD_MAX) {
                $this->errors[$field] = 'Значение пароля не попадает в указанный диапазон длинны';
            }
        }
    }

    /**
     * Валидация совпадения введенных паролей
     * @param string $field - поле
     */
    protected function runRepeatValidator(string $field)
    {
        if ($this->formData['new_password'] !== $this->formData[$field]) {
            $this->errors[$field] = 'Указанные пароли не совпадают';
            $this->errors['new_password'] = $this->errors[$field];
        }
    }

    /**
     * Валидация соответствия текущего пароля и не равенство текущего и нового пароля
     * @param string $field - поле
     */
    protected function runCurrentValidator(string $field)
    {
        $user = $this->model->findItemById($this->formData['user_id']);

        if (password_verify($this->formData[$field], $user['pass'])) {
            if ($this->formData[$field] === $this->formData['new_password']) {
                $this->errors['new_password'] = 'Новый пароль не должен совпадать с текущим';
            }
        } else {
            $this->errors[$field] = 'Неправильно указан текущий пароль';
        }
    }
}
