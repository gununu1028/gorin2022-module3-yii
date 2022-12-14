<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class AdminController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->render('index');
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(['login']);
    }

    // ???????????????????????????????????????
    public function actionNewItem()
    {
        return $this->render('new-item');
    }

    // ???????????????????????????????????????????????????????????????????????????????????????
    public function actionCreateItem()
    {
        $r = Yii::$app->request;
        $name = $r->post('name');
        $price = $r->post('price');
        $user_id = 1;
        $params = [
            'name' => $name,
            'price' => $price,
            'user_id' => $user_id,
        ];
        Yii::$app->db->createCommand()->insert('items', $params)->execute();
        Yii::$app->session->setFlash('newItemSubmitted');
        return $this->render('new-item');
    }

    // ?????????????????????????????????
    public function actionEditItem()
    {
        return $this->render('edit-item');
    }

    // ?????????????????????????????????????????????????????????????????????????????????
    public function actionUpdateItem()
    {
        $r = Yii::$app->request;
        $id = $r->post('id');
        $name = $r->post('name');
        $price = $r->post('price');
        $params = [
            'name' => $name,
            'price' => $price,
        ];
        Yii::$app->db->createCommand()->update('items', $params, 'id=:id', ['id' => $id])->execute();
        Yii::$app->session->setFlash('newItemSubmitted');
        return $this->render('edit-item');
    }

    // ?????????????????????????????????????????????????????????????????????????????????
    public function actionDeleteItem()
    {
        $r = Yii::$app->request;
        $id = $r->post('id');
        Yii::$app->db->createCommand()->delete('items', 'id=:id', ['id' => $id])->execute();
        return $this->render('delete-item');
    }

    // ?????????????????????????????????????????????
    public function actionNewSet()
    {
        return $this->render('new-set');
    }

    // ?????????????????????????????????????????????????????????????????????????????????????????????
    public function actionCreateSet()
    {
        Yii::$app->db->transaction(function ($d) {
            $r = Yii::$app->request;
            $name = $r->post('name');
            $select_items = $r->post('select_items');
            $user_id = 1;
            $params = [
                'name' => $name,
                'user_id' => $user_id,
            ];
            // sets????????????????????????????????????
            $d->createCommand()->insert('sets', $params)->execute();
            $created_set = $d->createCommand('SELECT * FROM sets ORDER BY id desc')->queryOne();
            $set_id = $created_set['id'];
            for ($i = 0; $i < count($select_items); $i++) {
                $item_id = $select_items[$i];
                $params = [
                    'set_id' => $set_id,
                    'item_id' => $item_id,
                ];
                // set_items????????????????????????????????????
                $d->createCommand()->insert('set_items', $params)->execute();
            }
        });
        Yii::$app->session->setFlash('createdSet');
        return $this->render('new-set');
    }

    // ??????????????????????????????????????????
    public function actionShowSet()
    {
        return $this->render('show-set');
    }

    // ??????????????????????????????????????????
    public function actionEditSet()
    {
        return $this->render('edit-set');
    }

    // ??????????????????????????????????????????????????????????????????????????????????????????
    public function actionUpdateSet()
    {
        Yii::$app->db->transaction(function ($d) {
            $r = Yii::$app->request;
            $set_id = $r->post('id');
            $name = $r->post('name');
            $select_items = $r->post('select_items');
            $user_id = 1;
            $params = [
                'name' => $name,
                'user_id' => $user_id,
            ];
            // sets??????????????????????????????????????????
            $d->createCommand()->update('sets', $params, 'id=:id', ['id' => $set_id])->execute();
            // set_items??????????????????????????????????????????????????????
            $d->createCommand()->delete('set_items', 'set_id=:set_id', ['set_id' => $set_id])->execute();
            for ($i = 0; $i < count($select_items); $i++) {
                $item_id = $select_items[$i];
                $params = [
                    'set_id' => $set_id,
                    'item_id' => $item_id,
                ];
                // set_items????????????????????????????????????
                $d->createCommand()->insert('set_items', $params)->execute();
            }
        });
        Yii::$app->session->setFlash('updatedSet');
        return $this->render('edit-set');
    }

    // ??????????????????????????????????????????????????????????????????????????????????????????
    public function actionDeleteSet()
    {
        Yii::$app->db->transaction(function ($d) {
            $r = Yii::$app->request;
            $id = $r->post('id');
            $d->createCommand()->delete('sets', 'id=:id', ['id' => $id])->execute();
            $d->createCommand()->delete('set_items', 'set_id=:set_id', ['set_id' => $id])->execute();
        });
        return $this->render('delete-set');
    }

    // ???????????????????????????
    public function actionShowCoupon()
    {
        return $this->render('show-coupon');
    }

    // ???????????????????????????????????????
    public function actionNewCoupon()
    {
        return $this->render('new-coupon');
    }

    // ???????????????????????????????????????????????????????????????????????????????????????
    public function actionCreateCoupon()
    {
        $r = Yii::$app->request;
        $code = $r->post('code');
        $discount_price = $r->post('discount_price');
        $user_id = 1;
        $params = [
            'code' => $code,
            'discount_price' => $discount_price,
            'user_id' => $user_id,
        ];
        Yii::$app->db->createCommand()->insert('coupons', $params)->execute();
        Yii::$app->session->setFlash('createdCoupon');
        return $this->render('new-coupon');
    }

    // ?????????????????????????????????????????????????????????????????????????????????
    public function actionDeleteCoupon()
    {
        $r = Yii::$app->request;
        $id = $r->post('id');
        Yii::$app->db->createCommand()->delete('coupons', 'id=:id', ['id' => $id])->execute();
        return $this->render('delete-coupon');
    }
}
