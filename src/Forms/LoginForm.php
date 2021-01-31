<?php

namespace App\Forms;

/**
 * Форма входа в приложение
 */
class LoginForm extends BaseForm
{
    public $name = 'sign_in';

    public $dataFields = ['login', 'password', 'database'];

    protected $rules = [
        ['required', ['login', 'password']]
    ];
}
