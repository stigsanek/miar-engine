<?php

namespace App\Components;

/**
 * Компонент рендеринга шаблонов страниц
 */
class View
{
    /**
     * Параметры шаблона
     */
    private $layoutParams = [];

    /**
     * Устанавливает параметры в $layoutParams
     * @param string $key - ключ
     * @param mixed $value- значение
     */
    public function setLayoutParam(string $key, $value)
    {
        $this->layoutParams[$key] = $value;
    }

    /**
     * Рендерит готовую страницу
     * @param string $content - контент основной части
     */
    public function renderPage(string $content)
    {
        $parameters = array_merge($this->layoutParams, [
            'content' => $content,
            'alerts' => Session::getAlert(),
        ]);

        $this->renderTemplate('layout/layout', $parameters, true);
    }

    /**
     * Рендерит шаблон
     * @param string $viewName - имя шаблона
     * @param array $parameters - параметры
     * @param boolean $isPrint - флаг отображения шаблона
     * @return string
     */
    public function renderTemplate(string $viewName, $parameters = [], $isPrint = false)
    {

        $template = ROOT . '/views/' . $viewName . '.php';

        extract($parameters);

        ob_start();
        include $template;

        if ($isPrint) {
            echo ob_get_clean();
        } else {
            return ob_get_clean();
        }
    }
}
