<?php
/**
 * Файл класса UpdateAction
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\web\actions;

use Exception;
use yii\web\Response;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use chulakov\model\services\Service;
use chulakov\model\exceptions\NotFoundModelException;
use chulakov\web\Controller;

class UpdateAction extends Action
{
    /**
     * @var Service
     */
    protected $service;

    /**
     * Конструктор действия модификации сущности
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
     * Выполнение действия модификации сущности
     *
     * @param integer $id
     * @return string|Response
     * @throws BadRequestHttpException
     * @throws HttpException
     */
    public function run($id)
    {
        try {

            $model = $this->service->findOne($id);
            $form = $this->service->form($model);
            $request = \Yii::$app->request;

            if ($form->load($request->post()) && $form->validate()) {
                if ($this->service->update($model, $form)) {
                    return $this->backRedirect($request, $model);
                }
            }

            return $this->render('update', [
                'model' => $form,
            ]);

        } catch (HttpException $e) {
            throw $e;
        } catch (NotFoundModelException $e) {
            throw new NotFoundHttpException($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
