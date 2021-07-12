<?php

namespace App\Components;

/**
 * File upload component
 */
class Uploader
{
    /**
     * File
     * @var mixed
     */
    private $file;

    /**
     * Constructor
     * @param mixed $file - file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Generates file name
     * @param string $prefix - name prefix
     * @return string
     */
    public function generateFileName(string $prefix)
    {
        if (is_uploaded_file($this->file['tmp_name'])) {
            $name = uniqid($prefix);
            $extension = pathinfo($this->file['name'], PATHINFO_EXTENSION);
            $filename = $name . '_' . date('Ymd') . '.' . $extension;

            return $filename;
        }
    }

    /**
     * Uploads a file to the server
     * @param string $fileName - file name
     */
    public function upload(string $fileName)
    {
        move_uploaded_file($this->file['tmp_name'], UPLOAD_PATH . DIRECTORY_SEPARATOR . $fileName);
    }
}
