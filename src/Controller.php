<?php
/**
 * Файл класса Controller
 *
 * @copyright Copyright (c) 2018, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\web;

use Yii;
use chulakov\base\Controller as BaseController;

/**
 * Класс контроллер для работы с web
 */
abstract class Controller extends BaseController
{
    /**
     * @var array Список действия, которые должны запомнить URL для дальнейших редиректов
     */
    protected $saveActionsUrl = ['index'];

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        if (in_array($action->id, $this->saveActionsUrl)) {
            Yii::$app->user->setReturnUrl(Yii::$app->request->url);
        }
        return parent::afterAction($action, $result);
    }
}
