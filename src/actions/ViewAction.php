<?php
/**
 * Файл класса ViewAction
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\web\actions;

use yii\web\NotFoundHttpException;
use chulakov\model\services\Service;
use chulakov\model\exceptions\NotFoundModelException;
use chulakov\web\Controller;

class ViewAction extends Action
{
    /**
     * @var Service
     */
    protected $service;

    /**
     * Конструктор действия вывода информации о сущности
     *
     * @param string $id
     * @param Controller $controller
     * @param Service $service
     * @param array $config
     */
    public function __construct($id, Controller $controller, Service $service, array $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $controller, $config);
    }

    /**
     * Выполнение действия вывода информации о сущности
     *
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function run($id)
    {
        try {

            return $this->render('view', [
                'model' => $this->service->findOne($id),
            ]);

        } catch (NotFoundModelException $e) {
            throw new NotFoundHttpException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
