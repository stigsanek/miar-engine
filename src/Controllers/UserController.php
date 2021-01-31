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
        if ($this->userSession->isAuthUser()) {
            $this->redirect('/');
        }

        $form = new LoginForm();

        if ($this->request->isSubmitted($form->name)) {
            $this->request->loadData($form);

            if ($form->isReady()) {
                $userData = $this->userModel->verify($form->getFormData());
                $this->auth($userData);
            } else {
                http_response_code(400);
            }
        }

        $this->view->render('user/login', [
            'title' => 'Авторизация',
            'errors' => $this->setFormErrorAlert($form->getErrors())
        ], true);
    }

    /**
     * Action выхода из приложения
     */
    public function actionLogout()
    {
        if (!$this->request->isGet()) {
            $this->actionError(405);
        }

        $this->userSession->logout();
        $this->redirect('/');
    }

    /**
     * Action раздела профиля пользователя
     */
    public function actionInfo()
    {
        if (!$this->request->isGet()) {
            $this->actionError(405);
        }

        $context = $this->view->render('user/info');

        $this->view->render('layout/layout', [
            'title' => 'Информация | Профиль',
            'tab' => 'user',
            'content' => $this->view->render('user/index', ['context' => $context])
        ], true);
    }

    /**
     * Action раздела изменения пароля
     */
    public function actionSecurity()
    {
        $form = new PasswordForm();
        $form->setFormData('user_id', $this->userSession->getUserId());

        if ($this->request->isSubmitted($form->name)) {
            $this->request->loadData($form);

            if ($form->isReady()) {
                $this->userModel->changePassword($form->getFormData());

                if (!$this->userModel->isError) {
                    $this->userSession->setAlert('success', 'Операция успешно выполнена');
                    $this->redirect('/logout');
                } else {
                    $this->userSession->setAlert(
                        'danger',
                        'Ошибка: ' .  implode(', ', $this->userModel->getErrors())
                    );
                    http_response_code(400);
                }
            } else {
                http_response_code(400);
            }
        }

        $context = $this->view->render('user/security', [
            'errors' => $this->setFormErrorAlert($form->getErrors())
        ]);
        $content = $this->view->render('user/index', ['context' => $context]);

        $this->view->render('layout/layout', [
            'title' => 'Безопасность | Профиль',
            'tab' => 'user',
            'content' => $content
        ], true);
    }

    /**
     * Выполняет логику авторизации
     * @param array $userData - данные пользователя
     */
    private function auth(array $userData)
    {
        if (isset($userData['isActive'])) {
            $this->userSession->authenticate($userData);

            if ($userData['pass_status'] == '0') {
                $this->userSession->setAlert(
                    'warning',
                    'В целях безопасности, вам необходимо изменить пароль. '
                        . 'Пожалуйста, пройдите по <b><a href="/profile/security">ссылке</a></b>.'
                );
            }

            $this->redirect($this->request->getHttpReferer());
        }

        if (empty($userData)) {
            $this->userSession->setAlert('danger', 'Неправильно указан логин или пароль');
            http_response_code(400);
        }
    }
}
