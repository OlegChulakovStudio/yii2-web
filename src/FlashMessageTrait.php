<?php
/**
 * Файл класса FlashMessageTrait
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\web;

/**
 * Трейт удобного интерфейса добавления флеш сообщений из экшена или контроллера.
 */
trait FlashMessageTrait
{
    /**
     * Сообщение об успешности
     *
     * @param string $message
     * @return static
     */
    protected function successMessage($message)
    {
        return $this->addFlashMessage('success', $message);
    }

    /**
     * Информационное сообщение
     *
     * @param string $message
     * @return static
     */
    protected function infoMessage($message)
    {
        return $this->addFlashMessage('info', $message);
    }

    /**
     * Сообщение с предупреждением
     *
     * @param string $message
     * @return static
     */
    protected function warningMessage($message)
    {
        return $this->addFlashMessage('warning', $message);
    }

    /**
     * Сообщение с предупреждением об ошибке
     *
     * @param string $message
     * @return static
     */
    protected function errorMessage($message)
    {
        return $this->addFlashMessage('error', $message);
    }

    /**
     * Добавление сообщения в список флеш сообщений
     *
     * @param string $type
     * @param string $message
     * @return static
     */
    protected function addFlashMessage($type, $message)
    {
        \Yii::$app->session->setFlash($type, $message);
        return $this;
    }
}
