<?php

namespace app\controllers\api;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\db\Query;

class OrderController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $r = Yii::$app->request;

        $item_id = $r->getBodyParam('item_id');
        $set_id = $r->getBodyParam('sets_id');
        $address = $r->getBodyParam('address');
        $params = [
            'item_id' => $item_id,
            'set_id' => $set_id,
            'delivery_address' => $address,
        ];
        Yii::$app->db->createCommand()->insert('orders', $params)->execute();
        Yii::$app->response->statusCode = 201;
        return 'success';
    }
}
