<?php

namespace App\Components;

/**
 * Компонент рендеринга шаблонов страниц
 */
class View
{
    /**
     * Компонент текущей сессии пользователя
     */
    private $userSession;

    /**
     * Конструктор
     * @param object $userSession - объект сессии пользователя
     */
    public function __construct(object $userSession)
    {
        $this->userSession = $userSession;
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
        $params['user'] = $this->userSession;

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
