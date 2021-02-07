<?php

namespace App\Controllers;

/**
 * Admin panel controller
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
            'title' => 'Admin panel',
            'tab' => 'admin',
            'content' => $this->view->render('admin/index')
        ], true);
    }
}
