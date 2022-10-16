<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
<div>
    <table>
        <tr>
            <th>ID</th>
            <th>商品名</th>
        </tr>
        <tr>
            <td>
                <?= $model['id'] ?>
            </td>
            <td>
                <?= $model['name'] ?>
            </td>
        </tr>
    </table>
</div>