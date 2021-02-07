<?php

namespace App\Forms;

/**
 * Application login form
 */
class LoginForm extends BaseForm
{
    public $name = 'sign_in';

    public $dataFields = ['login', 'password', 'database'];

    protected $rules = [
        ['required', ['login', 'password']]
    ];
}
