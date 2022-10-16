<?php

namespace app\controllers\api;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use yii\db\Query;

class ItemsController extends Controller
{
    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $r = Yii::$app->request;
        $query = new Query;
        $query->select('*')->from('items');
        $where = [];

        $shop_id = $r->get('shop_id');
        if ($shop_id) {
            $where['user_id'] = $shop_id;
        }

        $price = $r->get('price');
        if ($price) {
            $where['price'] = $price;
        }

        $query->where($where);

        $title = $r->get('title');
        if ($title) {
            $query->andWhere(['like', 'name', $title]);
        }

        $items = $query->all();
        return $items;
    }
}
