<?php

namespace App\Controllers;

/**
 * Home page controller
 */
class MainController extends BaseController
{
    public function index()
    {
        if (!$this->request->isGet()) {
            $this->actionError(405);
        }

        $this->view->render('layout/layout', [
            'title' => 'Home',
            'tab' => 'main',
            'content' => $this->view->render('main/index')
        ], true);
    }
}
