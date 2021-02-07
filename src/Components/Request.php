<?php

namespace App\Components;

/**
 * Request processing component
 */
class Request
{
    /**
     * Checks incoming request for GET type
     * @return bool
     */
    public function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Checks incoming request for POST type
     * @return bool
     */
    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Returns the address of the previous page
     * @return string
     */
    public function getHttpReferer()
    {
        return $_SERVER['HTTP_REFERER'];
    }

    /**
     * Returns the GET request parameter
     * @param string $key - parameter key
     * @return string
     */
    public function getParam(string $key)
    {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }
    }

    /**
     * Returns the POST request parameter
     * @param string $key - parameter key
     * @return string
     */
    public function getPostParam(string $key)
    {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }
    }

    /**
     * Loads data into a form
     * @param object $form - form
     * @param bool $isTrim - whitespace clipping flag
     */
    public function loadData(object $form, bool $isTrim = true)
    {
        foreach ($form->dataFields as $field) {
            if (isset($_POST[$field])) {
                if ($isTrim) {
                    $form->setFormData($field, trim($_POST[$field]));
                } else {
                    $form->setFormData($field, $_POST[$field]);
                }
            }
        }

        foreach ($form->fileFields as $field) {
            if (isset($_FILES[$field])) {
                $form->setFormFiles($field, $_FILES[$field]);
            }
        }
    }

    /**
     * Form submission confirmation
     * @param string $name - the name of the submit button in the form
     * @return bool
     */
    public function isSubmitted(string $name)
    {
        return isset($_POST[$name]);
    }

    /**
     * Returns the path of the route
     * @return string
     */
    public static function getUri()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }
}
