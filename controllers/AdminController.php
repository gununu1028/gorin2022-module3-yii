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

    // 「商品情報の新規登録」画面
    public function actionNewItem()
    {
        return $this->render('new-item');
    }

    // 「商品情報の新規登録」画面でボタンをクリックしたあとの処理
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

    // 「商品情報の編集」画面
    public function actionEditItem()
    {
        return $this->render('edit-item');
    }

    // 「商品情報の編集」画面でボタンをクリックしたあとの処理
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

    // 商品情報リストで「削除」ボタンをクリックしたあとの処理
    public function actionDeleteItem()
    {
        $r = Yii::$app->request;
        $id = $r->post('id');
        Yii::$app->db->createCommand()->delete('items', 'id=:id', ['id' => $id])->execute();
        return $this->render('delete-item');
    }

    // 「セットメニュー新規登録」画面
    public function actionNewSet()
    {
        return $this->render('new-set');
    }

    // 「セットメニュー新規登録」画面でボタンをクリックしたあとの処理
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
            // setsテーブルにレコードを作る
            $d->createCommand()->insert('sets', $params)->execute();
            $created_set = $d->createCommand('SELECT * FROM sets ORDER BY id desc')->queryOne();
            $sets_id = $created_set['id'];
            for ($i = 0; $i < count($select_items); $i++) {
                $item_id = $select_items[$i];
                $params = [
                    'sets_id' => $sets_id,
                    'item_id' => $item_id,
                ];
                // set_itemsテーブルにレコードを作る
                $d->createCommand()->insert('set_items', $params)->execute();
            }
        });
        Yii::$app->session->setFlash('createdSet');
        return $this->render('new-set');
    }

    // 「セットメニューリスト」画面
    public function actionShowSet()
    {
        return $this->render('show-set');
    }

    // 「セットメニューの編集」画面
    public function actionEditSet()
    {
        return $this->render('edit-set');
    }

    // 「セットメニューの編集」画面でボタンをクリックしたあとの処理
    public function actionUpdateSet()
    {
        Yii::$app->db->transaction(function ($d) {
            $r = Yii::$app->request;
            $sets_id = $r->post('id');
            $name = $r->post('name');
            $select_items = $r->post('select_items');
            $user_id = 1;
            $params = [
                'name' => $name,
                'user_id' => $user_id,
            ];
            // setsテーブルのレコードを更新する
            $d->createCommand()->update('sets', $params, 'id=:id', ['id' => $sets_id])->execute();
            // set_itemsテーブルにある古いレコードを削除する
            $d->createCommand()->delete('set_items', 'sets_id=:sets_id', ['sets_id' => $sets_id])->execute();
            for ($i = 0; $i < count($select_items); $i++) {
                $item_id = $select_items[$i];
                $params = [
                    'sets_id' => $sets_id,
                    'item_id' => $item_id,
                ];
                // set_itemsテーブルにレコードを作る
                $d->createCommand()->insert('set_items', $params)->execute();
            }
        });
        Yii::$app->session->setFlash('updatedSet');
        return $this->render('edit-set');
    }

    // セットメニューリストで「削除」ボタンをクリックしたあとの処理
    public function actionDeleteSet()
    {
        Yii::$app->db->transaction(function ($d) {
            $r = Yii::$app->request;
            $id = $r->post('id');
            $d->createCommand()->delete('sets', 'id=:id', ['id' => $id])->execute();
            $d->createCommand()->delete('set_items', 'sets_id=:sets_id', ['sets_id' => $id])->execute();
        });
        return $this->render('delete-set');
    }
}
