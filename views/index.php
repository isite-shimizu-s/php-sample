<?php

declare(strict_types=1);
/** @var array $errors */
/** @var string $csrf */
/** @var array $posts */
?>
<h1>ミニ掲示板（SQLite）</h1>

<?php if ($errors): ?>
    <ul style="color: red;">
        <?php foreach ($errors as $e): ?>
            <li><?= h($e) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<h2>新規投稿</h2>
<form method="post" action="/">
    <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
    <input type="hidden" name="action" value="create">
    <div>
        <label>名前: <input type="text" name="name"></label>
    </div>
    <div>
        <label>メッセージ:<br>
            <textarea name="message" rows="4" cols="50"></textarea>
        </label>
    </div>
    <button type="submit">投稿</button>
</form>

<hr>

<h2>投稿一覧</h2>
<?php if (!$posts): ?>
    <p>まだ投稿がありません。</p>
<?php endif; ?>

<?php foreach ($posts as $p): ?>
    <div class="post">
        <div class="meta">
            <div>
                #<?= (int)$p['id'] ?> /
                <?= h($p['created_at']) ?> /
                <?= h($p['name']) ?>
            </div>
            <div class="actions">
                <a href="/?edit=<?= (int)$p['id'] ?>">編集</a>
                <form method="post" action="/" class="inline" onsubmit="return confirm('削除しますか？');">
                    <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                    <button type="submit" class="danger">削除</button>
                </form>
            </div>
        </div>
        <pre><?= h($p['message']) ?></pre>
    </div>
<?php endforeach; ?>