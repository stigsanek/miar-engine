<?php

namespace App\Controllers;

/**
 * Контроллер панели администратора
 */
class AdminController extends BaseController
{
    public function index()
    {
        if (!$this->request->isGet()) {
            $this->actionError(405);
        }

        $this->checkAdmin();

        $this->view->render('layout/layout', [
            'title' => 'Панель администратора',
            'tab' => 'admin',
            'content' => $this->view->render('admin/index')
        ], true);
    }
}
