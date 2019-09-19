<?php
/**
 * Файл класса IndexAction
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\web\actions;

use chulakov\model\services\Service;
use chulakov\web\Controller;

class IndexAction extends Action
{
    /**
     * @var array Базовая конфигурация поисковой модели
     */
    public $searchConfig = [];
    /**
     * @var Service
     */
    protected $service;

    /**
     * Конструктор действия вывода списка сущностей
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
     * Выполнение действия вывода списка сущностей
     *
     * @return string
     */
    public function run()
    {
        $searchModel = $this->service->search($this->searchConfig);
        $dataProvider = $searchModel->search(\Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
