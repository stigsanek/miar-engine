<?php

namespace App\Forms;

/**
 * Форма изменения пароля
 */
class PasswordForm extends BaseForm
{
    /**
     * Минимальная длина пароля
     */
    private const PASSWORD_MIN = 8;

    /**
     * Максимальная длина пароля
     */
    private const PASSWORD_MAX = 20;

    /**
     * Имя кнопки отправки формы
     */
    public $name = 'change';

    /**
     * Названия обычных полей
     */
    public $dataFields = ['cur_password', 'new_password', 'new_password_repeat'];

    /**
     * Правила валидации
     */
    protected $rules = [
        ['char', ['new_password', 'new_password_repeat']],
        ['length', ['new_password', 'new_password_repeat']],
        ['repeat', 'new_password_repeat'],
        ['required', ['cur_password', 'new_password', 'new_password_repeat']]
    ];

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

            if (($length > 0 && $length < self::PASSWORD_MIN) || $length > self::PASSWORD_MAX) {
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
}
