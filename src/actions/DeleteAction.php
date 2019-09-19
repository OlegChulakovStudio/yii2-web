<?php
/**
 * Файл класса DeleteAction
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\web\actions;

use Exception;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use chulakov\model\services\Service;
use chulakov\web\Controller;

class DeleteAction extends PostAction
{
    /**
     * @var string
     */
    public $errorMessage = 'Не удалось удалить запись.';
    /**
     * @var string
     */
    public $successMessage = 'Запись успешно удалена.';
    /**
     * @var Service
     */
    protected $service;

    /**
     * Конструктор действия удаления сущности
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
     * Выполнение действия удаления сущности
     *
     * @param integer $id
     * @return array|Response
     * @throws BadRequestHttpException
     */
    public function run($id)
    {
        try {

            if ($this->service->delete($id)) {
                return $this->formatResponse([
                    'message' => $this->successMessage,
                ]);
            }
            throw new Exception($this->errorMessage);

        } catch (Exception $e) {
            return $this->formatResponse($e);
        }
    }
}
