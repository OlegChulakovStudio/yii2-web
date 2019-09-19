<?php
/**
 * Файл класса ToggleStatusAction
 *
 * @copyright Copyright (c) 2018, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\web\actions;

use Exception;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\base\InvalidConfigException;
use chulakov\model\services\Service;
use chulakov\model\exceptions\NotFoundModelException;
use chulakov\web\Controller;

class ToggleStatusAction extends PostAction
{
    /**
     * @var string Атрибут активности
     */
    public $attribute = 'status';
    /**
     * @var int Статус переключения в режим - включено
     */
    public $statusOn = 1;
    /**
     * @var int Статус переключения в режим - выключено
     */
    public $statusOff = 0;
    /**
     * @var string
     */
    public $errorMessage = 'Не удалось сменить статус записи.';
    /**
     * @var string
     */
    public $successMessage = 'Статус записи успешно изменен.';
    /**
     * @var Service
     */
    protected $service;

    /**
     * Конструктор действия смены статуса активности сущности
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
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty($this->attribute)) {
            throw new InvalidConfigException('Требуется указать атрибут статуса.');
        }
    }

    /**
     * Выполнение смены активности объекта
     *
     * @param integer $id
     * @return array|Response
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function run($id)
    {
        try {

            $model = $this->service->findOne($id);
            if ($model->hasProperty($this->attribute)) {
                $model->{$this->attribute} = $this->toggleStatus(
                    $model->{$this->attribute}
                );
                $this->service->save($model);
                return $this->formatResponse([
                    'message' => $this->successMessage,
                ]);
            }
            throw new Exception($this->errorMessage);

        } catch (NotFoundModelException $e) {
            throw new NotFoundHttpException($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            return $this->formatResponse($e);
        }
    }

    /**
     * Выбор статуса для смены в модели
     *
     * @param integer $status
     * @return integer
     */
    protected function toggleStatus($status)
    {
        return $this->statusOn == $status
            ? $this->statusOff
            : $this->statusOn;
    }
}
