<?php
/**
 * Файл класса ToggleActivityAction
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
use chulakov\web\Controller;
use chulakov\model\exceptions\NotFoundModelException;
use chulakov\model\services\Service;

class ToggleActivityAction extends PostAction
{
    /**
     * @var string Атрибут активности
     */
    public $attribute = 'is_active';
    /**
     * @var string
     */
    public $errorMessage = 'Не удалось сменить активность записи.';
    /**
     * @var string
     */
    public $successMessage = 'Активность запись успешно изменена.';
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
            throw new InvalidConfigException('Требуется указать атрибут активности.');
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
                $model->{$this->attribute} = $this->toggleActivity(
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
     * Выбор активности для смены в модели
     *
     * @param integer $isActive
     * @return integer
     */
    protected function toggleActivity($isActive)
    {
        return $isActive ? 0 : 1;
    }
}
