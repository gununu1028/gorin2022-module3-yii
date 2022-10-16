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
}
