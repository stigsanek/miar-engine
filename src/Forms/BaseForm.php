<?php

namespace App\Forms;

use App\Components\Session;

/**
 * Базовая форма
 */
class BaseForm
{
    /**
     * Имя кнопки отправки формы
     */
    public $name;

    /**
     * Названия обычных полей
     */
    public $dataFields = [];

    /**
     * Названия полей файлов
     */
    public $fileFields = [];

    /**
     * Данные обычных полей
     */
    protected $formData = [];

    /**
     * Данные полей файлов
     */
    protected $formFiles = [];

    /**
     * Правила валидации
     */
    protected $rules = [];

    /**
     * Ошибки валидации
     */
    protected $errors = [];

    /**
     * Флаг установки уведомлений в сессию
     */
    protected $isAlert = true;

    /**
     * Устанавливает значения обычным полям
     * @param string $field - имя поля
     * @param mixed $value - значение
     */
    public function setFormData(string $field, $value)
    {
        $this->formData[$field] = $value;
    }

    /**
     * Возвращает даннные обычных полей
     * @return array
     */
    public function getFormData()
    {
        return $this->formData;
    }

    /**
     * Устанавливает значения полям файлов
     * @param string $field - имя поля
     * @param mixed $value - значение
     */
    public function setFormFiles(string $field, $file)
    {
        $this->formFiles[$field] = $file;
    }

    /**
     * Возвращает даннные полей файлов
     * @return array
     */
    public function getFormFiles()
    {
        return $this->formFiles;
    }

    /**
     * Возвращает ошибки валидации
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Проверяет получение данных и запускает валидацию
     * @return boolean
     */
    public function isReady()
    {
        if (!empty($this->formData)) {
            $this->validate();

            if (empty($this->errors)) {
                return true;
            } else {

                if ($this->isAlert) {
                    Session::setAlert('danger', 'Пожалуйста, исправьте ошибки в форме');
                }

                return false;
            }
        } else {
            $this->errors['all'] = 'Отсутствуют данные формы';
            return false;
        }
    }

    /**
     * Запускает валидацию согласно массиву правил
     */
    protected function validate()
    {
        if (!empty($this->rules)) {
            foreach ($this->rules as $rule) {
                list($ruleName, $fields) = $rule;
                $this->runValidator($ruleName, $fields);
            }
        }
    }

    /**
     * Запускает необходимый метод валидации
     * @param string $name - имя правила
     * @param mixed $fields - поля
     */
    protected function runValidator(string $name, $fields)
    {
        $methodName = 'run' . ucfirst($name) . 'Validator';

        if (method_exists($this, $methodName)) {
            $this->$methodName($fields);
        }
    }

    /**
     * Валидация дат
     * @param array $fields - поля
     */
    protected function runDateValidator(array $fields)
    {
        foreach ($fields as $field) {
            if (!preg_match('/^20[0-9]{2}\-[0-1]{1}[0-9]{1}\-[0-3]{1}[0-9]{1}/', $this->formData[$field])) {
                $this->errors[$field] = 'Значение поля должно быть датой в формате ДД.ММ.ГГГГ';
            }
        }
    }

    /**
     * Валидация изображений
     * @param array $fields - поля
     */
    protected function runImageValidator(array $fields)
    {
        foreach ($fields as $field) {
            if (!empty($this->formFiles[$field]['tmp_name'])) {
                $types = ['image/jpeg', 'image/png'];
                $file = $this->formFiles[$field]['tmp_name'];
                $result = null;

                if (!empty($file)) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $file);
                    $result = in_array($mime, $types);
                }

                if (empty($result)) {
                    $this->errors[$field] = 'Загруженный файл должен быть изображением формата *.jpeg или *.png';
                }
            }
        }
    }

    /**
     * Валидация числовых полей
     * @param array $fields - поля
     */
    protected function runNumericValidator(array $fields)
    {
        foreach ($fields as $field) {
            if (!preg_match('/[0-9]+/', $this->formData[$field]) || $this->formData[$field] <= 0) {
                $this->errors[$field] = 'Значение поля должно быть положительным числом';
            }
        }
    }

    /**
     * Валидация обязательных полей
     * @param array $fields - поля
     */
    protected function runRequiredValidator(array $fields)
    {
        foreach ($fields as $field) {
            if (empty($this->formData[$field])) {
                $this->errors[$field] = 'Значение поля не должно быть пустым';
            }
        }
    }
}
