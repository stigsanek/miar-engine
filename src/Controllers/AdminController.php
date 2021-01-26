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

        $content = $this->view->renderTemplate('admin/index');

        $this->view->setLayoutParam('title', 'Панель администратора');
        $this->view->setLayoutParam('tab', 'admin');

        $this->view->renderPage($content);
    }
}
