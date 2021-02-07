<?php

namespace App\Controllers;

use App\Components\Request;
use App\Components\Session;
use App\Components\View;
use App\Models\User;

/**
 * Base controller
 */
class BaseController
{
    /**
     * Request processing component
     * @var object
     */
    protected $request;

    /**
     * User session component
     * @var object
     */
    protected $userSession;

    /**
     * User model
     * @var object
     */
    protected $userModel;

    /**
     * Views component
     * @var object
     */
    protected $view;

    /**
     * HTTP error codes
     * @var array
     */
    protected $httpErrorCodes = [
        400 => 'Bad Request',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed'
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->request = new Request();
        $this->userSession = new Session();
        $this->view = new View();
        $this->userModel = new User();
    }

    /**
     * Executes any logic before calling the action
     */
    public function beforeAction()
    {
        $this->view->setParams(['user' => $this->userSession]);
    }

    /**
     * Error page action
     * @param int $code - error code
     */
    public function actionError(int $code)
    {
        $this->view->render('error/error', [
            'error' => ['code' => $code, 'msg' => $this->httpErrorCodes[$code]],
            'title' => 'Error ' . $code
        ], true);

        http_response_code($code);
        exit;
    }

    /**
     * Checks user authentication
     */
    public function checkAuthUser()
    {
        if (!$this->userSession->isAuthUser()) {
            $controller = new UserController();
            $controller->beforeAction();
            $controller->login();
            exit;
        }
    }

    /**
     * Helper method checks the role "admin"
     * @return bool
     */
    protected function checkAdmin()
    {
        if ($this->userSession->isAdmin()) {
            return true;
        } else {
            $this->actionError(403);
        }
    }

    /**
     * Generates JSON Response
     * @param array $data - data
     * @param int $code - HTTP code
     */
    protected function renderJson(array $data, int $code)
    {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        http_response_code($code);
    }

    /**
     * Execute redirecting
     * @param string $route - route
     */
    protected function redirect(string $route)
    {
        header('Location: ' . $route);
        exit;
    }

    /**
     * Sets an error notification on a form
     * @param array $errors - form errors
     * @return array
     */
    protected function setFormErrorAlert(array $errors)
    {
        if (!empty($errors)) {
            $this->userSession->setAlert(
                'danger',
                'Please correct errors in the form'
            );
        }

        return $errors;
    }
}
