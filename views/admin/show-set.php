<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<h1>セットメニューリスト</h1>
<?php
$sets = Yii::$app->db->createCommand('SELECT * FROM sets')->queryAll();
?>
<div style="margin-bottom:20px;">
    <table>
        <tr>
            <th>ID</th>
            <th>セットメニュー名</th>
            <th></th>
            <th></th>
        </tr>
        <?php for ($i = 0; $i < count($sets); $i++) : ?>
            <tr>
                <td>
                    <?= $sets[$i]['id'] ?>
                </td>
                <td>
                    <?= $sets[$i]['name'] ?>
                </td>
                <td>
                    <a href="<?= Url::toRoute(['admin/edit-set/', 'id' => $sets[$i]['id']]) ?>">編集</a>
                </td>
                <td>
                    <?php
                    $form = ActiveForm::begin(
                        ['action' => '/admin/delete-set']
                    );
                    ?>
                    <input type="hidden" name="id" value="<?= $sets[$i]['id'] ?>">
                    <input type="submit" class="btn btn-link p-0 m-0" value="削除" onclick="clickDeleteButton(event)">
                    <?php ActiveForm::end(); ?>
                </td>
            </tr>
        <?php endfor; ?>
    </table>
</div>
<p>
    <a href="<?= Url::toRoute('admin/new-set') ?>">セットメニューの新規登録</a>
</p>
<script>
    function clickDeleteButton(event) {
        event.preventDefault();
        if (confirm('削除してよろしいですか？')) {
            event.target.parentElement.submit();
        }
    }
</script>