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
            $this->actionError(405);
        }

        $this->view->render('layout/layout', [
            'title' => 'Главная',
            'tab' => 'main',
            'content' => $this->view->render('main/index')
        ], true);
    }
}
