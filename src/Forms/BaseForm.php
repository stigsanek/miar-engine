<?php

namespace App\Forms;

/**
 * Base form
 */
class BaseForm
{
    /**
     * Name of the form submit button
     * @var string
     */
    public $name;

    /**
     * Field names
     * @var array
     */
    public $dataFields = [];

    /**
     * Files field names
     * @var array
     */
    public $fileFields = [];

    /**
     * Fields data
     * @var array
     */
    protected $formData = [];

    /**
     * Files fields data
     * @var array
     */
    protected $formFiles = [];

    /**
     * Validation rules
     * @var array
     */
    protected $rules = [];

    /**
     * Validation errors
     * @var array
     */
    protected $errors = [];

    /**
     * Data model
     * @var object
     */
    protected $model;

    /**
     * Sets values to fields
     * @param string $field - field
     * @param mixed $value - value
     */
    public function setFormData(string $field, $value)
    {
        $this->formData[$field] = $value;
    }

    /**
     * Returns data of fields
     * @return array
     */
    public function getFormData()
    {
        return $this->formData;
    }

    /**
     * Sets values to files fields
     * @param string $field - field
     * @param mixed $value - value
     */
    public function setFormFiles(string $field, $file)
    {
        $this->formFiles[$field] = $file;
    }

    /**
     * Returns data of files fields
     * @return array
     */
    public function getFormFiles()
    {
        return $this->formFiles;
    }

    /**
     * Sets validation errors
     * @param array $errors - form errors
     */
    public function setErrors(array $errors)
    {
        $curErrors = $this->errors;
        $this->errors = array_merge($curErrors, $errors);
    }

    /**
     * Returns validation errors
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Checks data receipt and starts validation
     * @return bool
     */
    public function isReady()
    {
        if (!empty($this->formData) || !empty($this->formFiles)) {
            $this->validate();

            if (empty($this->errors)) {
                return true;
            }
        }
    }

    /**
     * Runs validation according to an array of rules
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
     * Runs the required validation method
     * @param string $name - rule name
     * @param mixed $fields - fields
     */
    protected function runValidator(string $name, $fields)
    {
        $methodName = 'run' . ucfirst($name) . 'Validator';

        if (method_exists($this, $methodName)) {
            $this->$methodName($fields);
        }
    }

    /**
     * Date validation
     * @param array $fields - fields
     */
    protected function runDateValidator(array $fields)
    {
        foreach ($fields as $field) {
            if (!preg_match('/^20[0-9]{2}\-[0-1]{1}[0-9]{1}\-[0-3]{1}[0-9]{1}/', $this->formData[$field])) {
                $this->errors[$field] = 'The field value must be a date in the format DD.MM.YYYY';
            }
        }
    }

    /**
     * Image validation
     * @param array $fields - fields
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
                    $this->errors[$field] = 'The uploaded file must be an image in * .jpeg or * .png format';
                }
            }
        }
    }

    /**
     * Checking numeric fields
     * @param array $fields - fields
     */
    protected function runNumericValidator(array $fields)
    {
        foreach ($fields as $field) {
            if (!preg_match('/[0-9]+/', $this->formData[$field]) || $this->formData[$field] <= 0) {
                $this->errors[$field] = 'The field value must be a positive number';
            }
        }
    }

    /**
     * Validation of required fields
     * @param array $fields - fields
     */
    protected function runRequiredValidator(array $fields)
    {
        foreach ($fields as $field) {
            if (empty($this->formData[$field])) {
                $this->errors[$field] = 'The field value must not be empty';
            }
        }
    }
}
