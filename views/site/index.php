<?php
$this->title = '管理画面';
?>
<?php if (Yii::$app->user->isGuest) :  ?>
    <p>ログインしてください。</p>
<?php else : ?>
    <h1>商品情報リスト</h1>
    <?php
    $items = Yii::$app->db->createCommand('SELECT * FROM items')->queryAll();
    ?>
    <div>
        <table>
            <tr>
                <th>ID</th>
                <th>商品名</th>
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
                        <a href="/index.php?r=site/edit-item&id=<?= $items[$i]['id'] ?>">編集</a>
                    </td>
                </tr>
            <?php endfor; ?>
        </table>
        <a href="/index.php?r=site/new-item">商品情報の新規登録</a>
    </div>
<?php endif; ?>