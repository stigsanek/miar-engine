<?php

namespace App\Forms;

/**
 * Форма входа в приложение
 */
class LoginForm extends BaseForm
{
    /**
     * Имя кнопки отправки формы
     */
    public $name = 'sign_in';

    /**
     * Названия обычных полей
     */
    public $dataFields = ['login', 'password', 'database'];

    /**
     * Правила валидации
     */
    protected $rules = [
        ['required', ['login', 'password']]
    ];
}
