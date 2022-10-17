<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = '商品情報の新規登録';
?>
<div>
    <h1>商品情報の新規登録</h1>
    <?php if (Yii::$app->session->hasFlash('newItemSubmitted')) : ?>
        <p>商品情報が登録されました。</p>
    <?php else : ?>
        <div style="width: 300px;">
            <?php
            $form = ActiveForm::begin(
                ['action' => Url::toRoute('admin/create-item') ]
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