<?php

declare(strict_types=1);
/** @var array $errors */
/** @var string $csrf */
/** @var array $post */
?>
<div class="box">
    <h2>編集 #<?= (int)$post['id'] ?></h2>

    <?php if ($errors): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $e): ?>
                <li><?= h($e) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="/">
        <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?= (int)$post['id'] ?>">

        <div>
            <label>名前: <input type="text" name="name" value="<?= h((string)$post['name']) ?>"></label>
        </div>
        <div>
            <label>メッセージ:<br>
                <textarea name="message" rows="4" cols="50"><?= h((string)$post['message']) ?></textarea>
            </label>
        </div>

        <button type="submit">更新</button>
        <a href="/">キャンセル</a>
    </form>
</div>