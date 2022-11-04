<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<h1>クーポンリスト</h1>
<?php
$coupons = Yii::$app->db->createCommand('SELECT * FROM coupons')->queryAll();
?>
<div style="margin-bottom:20px;">
    <table>
        <tr>
            <th>ID</th>
            <th>クーポンコード</th>
            <th>割引金額</th>
            <th></th>
        </tr>
        <?php for ($i = 0; $i < count($coupons); $i++) : ?>
            <tr>
                <td>
                    <?= $coupons[$i]['id'] ?>
                </td>
                <td>
                    <?= $coupons[$i]['code'] ?>
                </td>
                <td>
                    <?= $coupons[$i]['discount_price'] ?>
                </td>
                <td>
                    <?php
                    $form = ActiveForm::begin(
                        ['action' => '/admin/delete-coupon']
                    );
                    ?>
                    <input type="hidden" name="id" value="<?= $coupons[$i]['id'] ?>">
                    <input type="submit" class="btn btn-link p-0 m-0" value="削除" onclick="clickDeleteButton(event)">
                    <?php ActiveForm::end(); ?>
                </td>
            </tr>
        <?php endfor; ?>
    </table>
</div>
<p>
    <a href="<?= Url::toRoute('admin/new-coupon') ?>">クーポンの新規登録</a>
</p>
<script>
    function clickDeleteButton(event) {
        event.preventDefault();
        if (confirm('削除してよろしいですか？')) {
            event.target.parentElement.submit();
        }
    }
</script>