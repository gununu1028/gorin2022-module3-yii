<?php

/** @var yii\web\View $this */

use yii\widgets\ListView;
use yii\data\SqlDataProvider;
use yii\bootstrap5\Nav;

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
    ?>
    <div>
        <table>
            <tr>
                <th>ID</th>
                <th>商品名</th>
            </tr>
            <?php
            echo ListView::widget([
                'dataProvider' => $p,
                'itemView' => '_items',
            ]);
            ?>
        </table>
    </div>
    <?php
    echo Nav::widget([
        'items' => [
            ['label' => '商品情報の新規登録', 'url' => ['/site/new-item']],
        ]
    ]);
    ?>
<?php endif; ?>