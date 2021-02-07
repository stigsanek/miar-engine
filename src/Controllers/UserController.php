<?php

namespace App\Controllers;

use App\Forms\LoginForm;
use App\Forms\PasswordForm;

/**
 * User controller
 */
class UserController extends BaseController
{
    /**
     * Application login action
     */
    public function login()
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
            'title' => 'Authorization',
            'errors' => $this->setFormErrorAlert($form->getErrors())
        ], true);
    }

    /**
     * Application exit action
     */
    public function logout()
    {
        if (!$this->request->isGet()) {
            $this->actionError(405);
        }

        $this->userSession->logout();
        $this->redirect('/');
    }

    /**
     * Profile action
     */
    public function info()
    {
        if (!$this->request->isGet()) {
            $this->actionError(405);
        }

        $context = $this->view->render('user/info');

        $this->view->render('layout/layout', [
            'title' => 'Information | Profile',
            'tab' => 'user',
            'content' => $this->view->render('user/index', ['context' => $context])
        ], true);
    }

    /**
     * Change password action
     */
    public function security()
    {
        $form = new PasswordForm();
        $form->setFormData('user_id', $this->userSession->getUserId());

        if ($this->request->isSubmitted($form->name)) {
            $this->request->loadData($form);

            if ($form->isReady()) {
                $this->userModel->changePassword($form->getFormData());

                if (!$this->userModel->isError) {
                    $this->userSession->setAlert('success', 'Completed successfully');
                    $this->redirect('/logout');
                } else {
                    $this->userSession->setAlert(
                        'danger',
                        'Error: ' . implode(', ', $this->userModel->getErrors())
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
            'title' => 'Security | Profile',
            'tab' => 'user',
            'content' => $content
        ], true);
    }

    /**
     * Performs authorization logic
     * @param array $userData - user data
     */
    private function auth(array $userData)
    {
        if (isset($userData['isActive'])) {
            $this->userSession->authenticate($userData);

            if ($userData['pass_status'] == '0') {
                $this->userSession->setAlert(
                    'warning',
                    'For security purposes, you need to '
                        . '<b><a href="/profile/security">change your password</a></b>'
                );
            }

            $this->redirect($this->request->getHttpReferer());
        }

        if (empty($userData)) {
            $this->userSession->setAlert('danger', 'Login or password is incorrect');
            http_response_code(400);
        }
    }
}
