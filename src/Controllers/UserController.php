<?php

namespace App\Controllers;

use App\Forms\LoginForm;
use App\Forms\PasswordForm;

/**
 * Контролер пользователя
 */
class UserController extends BaseController
{
    /**
     * Action входа в приложение
     */
    public function actionLogin()
    {
        $form = new LoginForm();

        if ($this->request->isSubmitted($form->name)) {
            $this->request->loadData($form);

            if ($form->isReady()) {
                $userData = $this->userModel->verify($form->getFormData());

                if ($this->userModel->isError) {
                    http_response_code(400);
                }

                if (!empty($userData)) {
                    $this->userSession->authenticate($userData);
                    $this->redirect();
                }
            } else {
                http_response_code(400);
            }
        }

        $this->view->renderTemplate('user/login', [
            'title' => 'Авторизация',
            'errors' => $form->getErrors(),
            'alerts' => $this->userSession->getAlert()
        ], true);
    }

    /**
     * Action выхода из приложения
     */
    public function actionLogout()
    {
        if (!$this->request->isGet()) {
            return $this->actionError(405);
        }

        $this->userSession->logout();
        $this->redirect();
    }

    /**
     * Action раздела профиля пользователя
     */
    public function actionInfo()
    {
        if (!$this->request->isGet()) {
            return $this->actionError(405);
        }

        $context = $this->view->renderTemplate('user/info', ['user' => $this->userSession]);
        $content = $this->view->renderTemplate('user/index', ['context' => $context]);

        $this->view->setLayoutParam('title', 'Информация · Профиль');
        $this->view->setLayoutParam('tab', 'profile');

        $this->view->renderPage($content);
    }

    /**
     * Action раздела изменения пароля
     */
    public function actionSecurity()
    {
        $form = new PasswordForm();

        if ($this->request->isSubmitted($form->name)) {
            $this->request->loadData($form);

            if ($form->isReady()) {
                $form->setFormData('user_id', $this->userSession->getUserId());

                $this->userModel->changePassword($form->getFormData());

                if (!$this->userModel->isError) {
                    $this->redirect('logout');
                } else {
                    http_response_code(400);
                }
            } else {
                http_response_code(400);
            }
        }

        $context = $this->view->renderTemplate('user/security', ['errors' => $form->getErrors()]);
        $content = $this->view->renderTemplate('user/index', ['context' => $context]);

        $this->view->setLayoutParam('title', 'Безопасность · Профиль');
        $this->view->setLayoutParam('tab', 'profile');

        $this->view->renderPage($content);
    }
}
