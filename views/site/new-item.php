<?php

use yii\bootstrap5\ActiveForm;

$this->title = '商品情報の新規登録';
?>
<div>
    <h1>商品情報の新規登録</h1>
    <?php if (Yii::$app->session->hasFlash('newItemSubmitted')) : ?>
        <p>新規登録しました。</p>
    <?php else : ?>
        <div style="width: 300px;">
            <?php
            $form = ActiveForm::begin(
                ['action' => '/index.php?r=site/create-item']
            );
            ?>
            <p>商品名<br>
                <input type="text" class="form-control" name="name">
            </p>
            <p>価格<br>
                <input type="text" class="form-control" name="price">
            </p>
            <p>
                <input type="submit" class="btn btn-primary" value="新規登録">
            </p>
            <?php ActiveForm::end(); ?>
        </div>
    <?php endif; ?>
</div>