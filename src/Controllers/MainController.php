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

        $this->view->renderFullPage([
            'title' => 'Главная',
            'tab' => 'main',
            'content' => $this->view->renderTemplate('main/index')
        ]);
    }
}
