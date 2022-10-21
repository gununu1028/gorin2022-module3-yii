<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'セットメニューの新規登録';
$items = Yii::$app->db->createCommand('SELECT * FROM items')->queryAll();
?>
<div>
    <h1>セットメニューの新規登録</h1>
    <?php if (Yii::$app->session->hasFlash('createdSet')) : ?>
        <p>セットメニューが登録されました。</p>
    <?php else : ?>
        <div style="width: 300px;">
            <?php
            $form = ActiveForm::begin(
                ['action' => Url::toRoute('admin/create-set')]
            );
            ?>
            <p>セットメニュー名<br>
                <input type="text" class="form-control" name="name">
            </p>
            <p>商品</p>
            <table style="margin-bottom: 20px;">
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>商品名</th>
                </tr>
                <?php for ($i = 0; $i < count($items); $i++) : ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="select_items[]" value="<?= $items[$i]['id'] ?>">
                        </td>
                        <td>
                            <?= $items[$i]['id'] ?>
                        </td>
                        <td>
                            <?= $items[$i]['name'] ?>
                        </td>
                    </tr>
                <?php endfor; ?>
            </table>
            <p>
                <input type="submit" class="btn btn-primary" value="新規登録">
            </p>
            <?php ActiveForm::end(); ?>
        </div>
    <?php endif; ?>
</div>