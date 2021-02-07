<?php

namespace App\Components;

/**
 * Views component
 */
class View
{
    /**
     * Parameters
     * @var array
     */
    private $params = [];

    /**
     * Adds parameters
     * @param array $params - parameters
     */
    public function setParams(array $params)
    {
        $curParams = $this->params;
        $this->params = array_merge($curParams, $params);
    }

    /**
     * Renders a view
     * @param string $viewName - view name
     * @param array $params - parameters
     * @param bool $isPrint - flag for outputting the view from the buffer
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
