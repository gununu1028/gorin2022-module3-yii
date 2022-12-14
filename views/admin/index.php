<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '管理画面';
?>
<?php if (Yii::$app->user->isGuest) :  ?>
    <p>ログインしてください。</p>
<?php else : ?>
    <h1>商品情報リスト</h1>
    <?php
    $items = Yii::$app->db->createCommand('SELECT * FROM items')->queryAll();
    ?>
    <div style="margin-bottom:20px;">
        <table>
            <tr>
                <th>ID</th>
                <th>商品名</th>
                <th></th>
                <th></th>
            </tr>
            <?php for ($i = 0; $i < count($items); $i++) : ?>
                <tr>
                    <td>
                        <?= $items[$i]['id'] ?>
                    </td>
                    <td>
                        <?= $items[$i]['name'] ?>
                    </td>
                    <td>
                        <a href="<?= Url::toRoute(['admin/edit-item/', 'id' => $items[$i]['id']]) ?>">編集</a>
                    </td>
                    <td>
                        <?php
                        $form = ActiveForm::begin(
                            ['action' => '/admin/delete-item']
                        );
                        ?>
                        <input type="hidden" name="id" value="<?= $items[$i]['id'] ?>">
                        <input type="submit" class="btn btn-link p-0 m-0" value="削除" onclick="clickDeleteButton(event)">
                        <?php ActiveForm::end(); ?>
                    </td>
                </tr>
            <?php endfor; ?>
        </table>
    </div>
    <p>
        <a href="<?= Url::toRoute('admin/new-item') ?>">商品情報の新規登録</a>
    </p>
    <p>
        <a href="<?= Url::toRoute('admin/new-coupon') ?>">クーポンの新規登録</a>
    </p>
<?php endif; ?>
<script>
    function clickDeleteButton(event) {
        event.preventDefault();
        if (confirm('削除してよろしいですか？')) {
            event.target.parentElement.submit();
        }
    }
</script>