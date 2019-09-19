<?php
/**
 * Файл класса PostAction
 *
 * @copyright Copyright (c) 2018, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\web\actions;

use yii\web\Response;
use yii\web\BadRequestHttpException;
use chulakov\base\response\HTTP;

/**
 * Базовое действие для обработки POST/Ajax запросов.
 */
abstract class PostAction extends Action
{
    /**
     * @var string|array|null Дефолтный роут возвращения обратно
     */
    public $defaultBackUrl = null;

    /**
     * @inheritdoc
     */
    protected function beforeRun()
    {
        $this->controller->enableCsrfValidation = false;
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
        }
        return parent::beforeRun();
    }

    /**
     * Форматирование ответа
     *
     * @param array|\Exception|null $message
     * @return array|Response
     * @throws BadRequestHttpException
     */
    protected function formatResponse($message = null)
    {
        // Проверка ошибки во время выполнения действия
        if ($message instanceof \Exception || $message instanceof \Throwable) {
            throw new BadRequestHttpException(
                $message->getMessage(), $message->getCode(), $message
            );
        }
        // Подготовка массива для анализа
        if (is_string($message) && !empty($message)) {
            $message = ['message' => $message];
        }
        if (empty($message) && !is_array($message)) {
            $message = [];
        }
        // Форматирование сообщения для ajax запроса
        if (\Yii::$app->request->isAjax) {
            return $this->formatAjax($message);
        }
        // Форматирование ответа для post запроса
        return $this->formatRedirect($message);
    }

    /**
     * Форматирование ajax ответа
     *
     * @param array $message
     * @return array
     */
    protected function formatAjax($message)
    {
        $result = ['success' => true];
        if (isset($message['status'])) {
            \Yii::$app->response->statusCode = $message['status'];
            if ($message['status'] >= 400) {
                $result['success'] = false;
            }
        } elseif (isset($message['error'])) {
            \Yii::$app->response->statusCode = HTTP::CLIENT_BAD_REQUEST;
            $result['message'] = $message['error'];
            $result['success'] = false;
        } else {
            \Yii::$app->response->statusCode = HTTP::SUCCESS_OK;
            if (isset($message['message'])) {
                $result['message'] = $message['message'];
            }
        }
        return $result;
    }

    /**
     * Форматирование редиректа
     *
     * @param array $message
     * @return Response
     */
    protected function formatRedirect($message)
    {
        if (isset($message['error'])) {
            \Yii::$app->session->setFlash('warning', $message['error']);
        } elseif (isset($message['message'])) {
            \Yii::$app->session->setFlash('info', $message['message']);
        }
        return $this->controller->goBack($this->defaultBackUrl);
    }
}
