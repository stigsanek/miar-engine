<?php

namespace App\Controllers;

use App\Components\Request;
use App\Components\Session;
use App\Components\View;
use App\Models\User;

/**
 * Базовый контроллер
 */
class BaseController
{
    /**
     * Компонент обработки запросов
     */
    protected $request;

    /**
     * Компонент текущей сессии пользователя
     */
    protected $userSession;

    /**
     * Модель пользователя
     */
    protected $userModel;

    /**
     * Компонент рендеринга шаблонов страниц
     */
    protected $view;

    /**
     * Коды ошибок HTTP
     */
    protected $httpErrorCodes = [
        400 => 'Bad Request',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed'
    ];

    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->request = new Request();
        $this->userSession = new Session();
    }

    /**
     * Выполняет любую логику перед вызовом action
     */
    public function beforeAction()
    {
        $this->userModel = new User();
        $this->view = new View($this->userSession);
    }

    /**
     * Action страницы ошибок
     * @param int $code - код ошибки
     */
    public function actionError(int $code)
    {
        $this->view->render('error/error', [
            'error' => ['code' => $code, 'msg' => $this->httpErrorCodes[$code]],
            'title' => 'Ошибка' . $code
        ], true);

        http_response_code($code);
        exit;
    }

    /**
     * Проверяет аутентификацию пользователя
     */
    public function checkAuthUser()
    {
        if (!$this->userSession->isAuthUser()) {
            $controller = new UserController();
            $controller->beforeAction();
            $controller->actionLogin();
            exit;
        }
    }

    /**
     * Проверяет роль admin
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
     * Формирует JSON Response
     * @param array $data - данные
     * @param int $code - HTTP-код
     */
    protected function returnJson(array $data, int $code)
    {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        http_response_code($code);
    }

    /**
     * Переадресует по заданному пути
     * @param mixed $path - путь
     */
    protected function redirect($path = null)
    {
        header('Location: ' . '/' . $path);
        exit;
    }
}
