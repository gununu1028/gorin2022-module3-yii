<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'セットメニューの編集';

$r = Yii::$app->request;
$set_id = $r->get('id');
$command = Yii::$app->db->createCommand('SELECT * FROM sets WHERE id=:id');
$s = $command->bindValue(':id', $set_id)->queryOne();
$items = Yii::$app->db->createCommand('SELECT * FROM items')->queryAll();

?>
<div>
    <h1>セットメニューの編集</h1>
    <?php if (Yii::$app->session->hasFlash('updatedSet')) : ?>
        <p>商品情報が更新されました。</p>
    <?php else : ?>
        <div style="width: 300px;">
            <?php
            $form = ActiveForm::begin(
                ['action' => Url::toRoute('admin/update-set')]
            );
            ?>
            <p>セットメニュー名<br>
                <input type="text" class="form-control" name="name" value="<?= $s['name'] ?>">
            </p>
            <p>商品</p>
            <table style="margin-bottom: 20px;">
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>商品名</th>
                </tr>
                <?php for ($i = 0; $i < count($items); $i++) : ?>
                    <?php
                    $command = Yii::$app->db->createCommand('SELECT * FROM set_items WHERE set_id=:set_id AND item_id=:item_id');
                    $params = [
                        ':set_id' => $set_id,
                        ':item_id' => $items[$i]['id']
                    ];
                    $set_items = $command->bindValues($params)->queryOne();
                    if ($set_items) :
                        $checked_text = 'checked';
                    else :
                        $checked_text = '';
                    endif;
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="select_items[]" value="<?= $items[$i]['id'] ?>" <?= $checked_text ?>>
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
                <input type="submit" class="btn btn-primary" value="保存">
            </p>
            <input type="hidden" name="id" value="<?= $s['id'] ?>">
            <?php ActiveForm::end(); ?>
        </div>
    <?php endif; ?>
</div>