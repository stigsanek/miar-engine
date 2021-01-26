<?php

namespace App\Components;

/**
 * Компонент загрузки файлов
 */
class Uploader
{
    /**
     * Файл
     */
    private $file;

    /**
     * Конструктор
     * @param mixed $file - файл
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Генерирует имя файла
     * @param string $prefix - префикс имени
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
     * Загружает файл на сервер
     * @param string $fileName - имя файла
     * @param string $path - путь загрузки
     */
    public function upload(string $fileName, $path = null)
    {
        move_uploaded_file($this->file['tmp_name'], UPLOAD_PATH . $path . DIRECTORY_SEPARATOR . $fileName);
    }
}
