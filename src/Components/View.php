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
    private $params = [];

    /**
     * Устанавливает параметры в $params
     * @param array $params - параметры
     */
    public function setParams(array $params)
    {
        $curParams = $this->params;
        $this->params = array_merge($curParams, $params);
    }

    /**
     * Рендерит готовую страницу
     * @param array $params - параметры
     */
    public function renderFullPage(array $params = [])
    {
        $allParams = array_merge($this->params, $params, [
            'alerts' => Session::getAlert()
        ]);

        $this->renderTemplate('layout/layout', $allParams, true);
    }

    /**
     * Рендерит шаблон
     * @param string $viewName - имя шаблона
     * @param array $params - параметры
     * @param boolean $isPrint - флаг отображения шаблона
     * @return string
     */
    public function renderTemplate(string $viewName, array $params = [], bool $isPrint = false)
    {

        $template = ROOT . '/views/' . $viewName . '.php';

        extract($params);

        ob_start();
        include $template;

        if ($isPrint) {
            echo ob_get_clean();
        } else {
            return ob_get_clean();
        }
    }
}
