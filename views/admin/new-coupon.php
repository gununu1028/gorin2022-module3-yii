<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'クーポンの新規登録';
?>
<div>
    <h1>クーポンの新規登録</h1>
    <?php if (Yii::$app->session->hasFlash('createdCoupon')) : ?>
        <p>クーポンが登録されました。</p>
    <?php else : ?>
        <div style="width: 300px;">
            <?php
            $form = ActiveForm::begin(
                ['action' => Url::toRoute('admin/create-coupon') ]
            );
            ?>
            <p>クーポンコード<br>
                <input type="text" class="form-control" name="code">
            </p>
            <p>割引金額<br>
                <input type="text" class="form-control" name="discount_price">
            </p>
            <p>
                <input type="submit" class="btn btn-primary" value="新規登録">
            </p>
            <?php ActiveForm::end(); ?>
        </div>
    <?php endif; ?>
</div>