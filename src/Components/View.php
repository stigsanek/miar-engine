<?php

namespace App\Components;

/**
 * Компонент рендеринга шаблонов страниц
 */
class View
{
    /**
     * Параметры для передачи в шаблон
     * @var array
     */
    private $params = [];

    /**
     * Добавляет параметры для передачи в шаблон
     * @param array $params - параметры
     */
    public function setParams(array $params)
    {
        $curParams = $this->params;
        $this->params = array_merge($curParams, $params);
    }

    /**
     * Рендерит шаблон
     * @param string $viewName - имя шаблона
     * @param array $params - параметры
     * @param bool $isPrint - флаг вывода шаблона из буфера
     * @return string
     */
    public function render(string $viewName, array $params = [], bool $isPrint = false)
    {

        $template = ROOT . '/views/' . $viewName . '.php';

        extract(array_merge($this->params, $params));

        ob_start();
        include $template;

        if ($isPrint) {
            echo ob_get_clean();
        } else {
            return ob_get_clean();
        }
    }
}
