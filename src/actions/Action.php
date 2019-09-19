<?php
/**
 * Файл класса Action
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\web\actions;

use yii\base\Model;
use yii\web\Request;
use yii\web\Response;
use chulakov\base\response\HTTP;
use chulakov\web\Controller;

abstract class Action extends \yii\base\Action
{
    /**
     * @var Controller
     */
    public $controller;
    /**
     * @var string
     */
    protected $refreshPostName = 'refresh';

    /**
     * Рендеринг шаблона
     *
     * @see \yii\web\Controller
     *
     * @param string $view
     * @param array $params
     * @return string
     */
    protected function render($view, $params = [])
    {
        return $this->controller->render($view, $params);
    }

    /**
     * Редирект на новый url
     *
     * @see \yii\web\Controller
     *
     * @param string|array $url
     * @param integer $statusCode
     * @return Response
     */
    protected function redirect($url, $statusCode = HTTP::REDIRECTION_FOUND)
    {
        return $this->controller->redirect($url, $statusCode);
    }

    /**
     * Редирект на экшен правильный экшен
     *
     * @param Request $request
     * @param Model|null $model
     * @return Response
     */
    protected function backRedirect($request, $model = null)
    {
        if ($request->post($this->refreshPostName)) {
            return $this->refresh($model);
        }
        return $this->listRedirect($model);
    }

    /**
     * Редирект на список
     *
     * @param Model|null $model
     * @return Response
     */
    protected function listRedirect($model = null)
    {
        return $this->redirect(['index']);
    }

    /**
     * Обновление страницы
     *
     * @param Model|null $model
     * @return Response
     */
    protected function refresh($model = null)
    {
        return $this->controller->refresh();
    }
}
