<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'セットメニューの新規登録';
?>
<div>
    <h1>セットメニューの新規登録</h1>
    <?php if (Yii::$app->session->hasFlash('createdSet')) : ?>
        <p>セットメニューが登録されました。</p>
    <?php else : ?>
        <div style="width: 300px;">
            <?php
            $form = ActiveForm::begin(
                ['action' => Url::toRoute('admin/create-set') ]
            );
            ?>
            <p>セットメニュー名<br>
                <input type="text" class="form-control" name="name">
            </p>
            <p>
                <input type="submit" class="btn btn-primary" value="新規登録">
            </p>
            <?php ActiveForm::end(); ?>
        </div>
    <?php endif; ?>
</div>