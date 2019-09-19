<?php
/**
 * Файл класса CreateAction
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\web\actions;

use Exception;
use yii\db\ActiveRecord;
use yii\web\Response;
use yii\web\HttpException;
use yii\web\BadRequestHttpException;
use chulakov\web\Controller;
use chulakov\model\services\Service;

class CreateAction extends Action
{
    /**
     * @var Service
     */
    protected $service;

    /**
     * Конструктор действия создания сущности
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
     * Выполнение действия создания сущности
     *
     * @return string|Response
     * @throws HttpException
     */
    public function run()
    {
        try {

            $form = $this->service->form();
            $request = \Yii::$app->request;

            if ($form->load($request->post()) && $form->validate()) {
                if ($model = $this->service->create($form)) {
                    return $this->backRedirect($request, $model);
                }
            }

            return $this->render('create', [
                'model' => $form,
            ]);

        } catch (HttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Обновление страницы
     *
     * @param ActiveRecord|null $model
     * @return Response
     */
    protected function refresh($model = null)
    {
        if (!empty($model)) {
            $params = $model->getPrimaryKey(true);
            $params[0] = 'update';
            return $this->redirect($params);
        }
        return parent::refresh($model);
    }
}
