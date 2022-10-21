<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

$this->title = '商品情報の編集';

$r = Yii::$app->request;
$id = $r->get('id');
$command =  Yii::$app->db->createCommand('SELECT * FROM items WHERE id=:id');
$item = $command->bindValue(':id', $id)->queryOne();

?>
<div>
    <h1>商品情報の編集</h1>
    <?php if (Yii::$app->session->hasFlash('newItemSubmitted')) : ?>
        <p>商品情報が更新されました。</p>
    <?php else : ?>
        <div style="width: 300px;">
            <?php
            $form = ActiveForm::begin(
                ['action' => Url::toRoute('admin/update-item') ]
            );
            ?>
            <p>商品名<br>
                <input type="text" class="form-control" name="name" value="<?= $item['name'] ?>">
            </p>
            <p>価格<br>
                <input type="text" class="form-control" name="price" value="<?= $item['price'] ?>">
            </p>
            <p>
                <input type="submit" class="btn btn-primary" value="保存">
            </p>
            <input type="hidden" name="id" value="<?= $item['id'] ?>">
            <?php ActiveForm::end(); ?>
        </div>
    <?php endif; ?>
</div>