<?php

namespace App\Controllers;

/**
 * Контроллер главной страницы
 */
class MainController extends BaseController
{
    public function actionIndex()
    {
        if (!$this->request->isGet()) {
            return $this->actionError(405);
        }

        $content = $this->view->renderTemplate('main/index');

        $this->view->setLayoutParam('title', 'Главная');
        $this->view->setLayoutParam('tab', 'main');

        $this->view->renderPage($content);
    }
}
