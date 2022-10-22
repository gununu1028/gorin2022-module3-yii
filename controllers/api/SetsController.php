<?php

namespace app\controllers\api;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\db\Query;

class SetsController extends Controller
{
    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $r = Yii::$app->request;
        $q = new Query;
        $where = [];
        $shop_id = $r->get('shop_id');
        if ($shop_id) {
            $where['user_id'] = $shop_id;
        }
        $q->select('*')->from('sets')->where($where);
        $sets = $q->all();

        for ($i = 0; $i < count($sets); $i++) {
            $q = new Query;
            $q->select('items.*')->from('items');
            $q->join('INNER JOIN', 'set_items', 'set_items.items_id = items.id');
            $q->where(['sets_id' => $sets[$i]['id']]);
            $items = $q->all();
            $sets[$i]['items'] = $items;
        }
        
        return $sets;
    }
}
