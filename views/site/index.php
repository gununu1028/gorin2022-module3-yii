<?php

/** @var yii\web\View $this */

use yii\widgets\ListView;
use yii\data\SqlDataProvider;

$this->title = '管理画面';
?>
<?php if (Yii::$app->user->isGuest) :  ?>
    <p>ログインしてください。</p>
<?php else : ?>
    <h1>商品情報リスト</h1>
    <?php
    // $items = Yii::$app->db->createCommand('SELECT * FROM items')->queryAll();
    $p = new SqlDataProvider([
        'sql' => 'SELECT * FROM items',
    ]);
    echo ListView::widget([
        'dataProvider' => $p,
        'itemView' => '_items',
    ]);
    ?>
<?php endif; ?>