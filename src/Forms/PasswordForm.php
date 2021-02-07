<?php

namespace App\Forms;

use App\Models\User;

/**
 * Password change form
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
     * Constructor
     */
    public function __construct()
    {
        $this->model = new User();
    }

    /**
     * Password characters check
     * @param array $fields - fields
     */
    protected function runCharValidator(array $fields)
    {
        foreach ($fields as $field) {
            if (!preg_match('/(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])/', $this->formData[$field])) {
                $this->errors[$field] = 'Password value does not meet requirements';
            }
        }
    }

    /**
     * Password length validation
     * @param array $fields - fields
     */
    protected function runLengthValidator(array $fields)
    {
        foreach ($fields as $field) {
            $length = strlen($this->formData[$field]);

            if (($length > 0 && $length < $this->model::PASSWORD_MIN) || $length > $this->model::PASSWORD_MAX) {
                $this->errors[$field] = 'Password value does not fall within the specified length range';
            }
        }
    }

    /**
     * Checking if the entered passwords match
     * @param string $field - field
     */
    protected function runRepeatValidator(string $field)
    {
        if ($this->formData['new_password'] !== $this->formData[$field]) {
            $this->errors[$field] = 'The specified passwords do not match';
            $this->errors['new_password'] = $this->errors[$field];
        }
    }

    /**
     * Checking whether the current password matches and the current and new password do not match
     * @param string $field - field
     */
    protected function runCurrentValidator(string $field)
    {
        $user = $this->model->findItemById($this->formData['user_id']);

        if (password_verify($this->formData[$field], $user['pass'])) {
            if ($this->formData[$field] === $this->formData['new_password']) {
                $this->errors['new_password'] = 'The new password must not be the same as the current one';
            }
        } else {
            $this->errors[$field] = 'Current password is incorrect';
        }
    }
}
