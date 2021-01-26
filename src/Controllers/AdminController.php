<?php

namespace App\Controllers;

/**
 * Контроллер панели администратора
 */
class AdminController extends BaseController
{
    public function actionIndex()
    {
        if (!$this->request->isGet()) {
            return $this->actionError(405);
        }

        $this->checkAdmin();

        $this->view->renderFullPage([
            'title' => 'Панель администратора',
            'tab' => 'admin',
            'content' => $this->view->renderTemplate('admin/index')
        ]);
    }
}
