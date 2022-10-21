<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

$this->title = 'セットメニューの編集';

$r = Yii::$app->request;
$id = $r->get('id');
$command =  Yii::$app->db->createCommand('SELECT * FROM sets WHERE id=:id');
$s = $command->bindValue(':id', $id)->queryOne();

?>
<div>
    <h1>セットメニューの編集</h1>
    <?php if (Yii::$app->session->hasFlash('updatedSet')) : ?>
        <p>商品情報が更新されました。</p>
    <?php else : ?>
        <div style="width: 300px;">
            <?php
            $form = ActiveForm::begin(
                ['action' => Url::toRoute('admin/update-set') ]
            );
            ?>
            <p>セットメニュー名<br>
                <input type="text" class="form-control" name="name" value="<?= $s['name'] ?>">
            </p>
            <p>
                <input type="submit" class="btn btn-primary" value="保存">
            </p>
            <input type="hidden" name="id" value="<?= $s['id'] ?>">
            <?php ActiveForm::end(); ?>
        </div>
    <?php endif; ?>
</div>