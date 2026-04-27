<?php

declare(strict_types=1);
/** @var string $title */
/** @var string $content */
?>
<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title><?= h($title) ?></title>
    <style>
        body {
            font-family: sans-serif;
        }

        .post {
            border: 1px solid #ddd;
            padding: 8px;
            margin: 8px 0;
        }

        .meta {
            color: #666;
            font-size: 12px;
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
        }

        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        form.inline {
            display: inline;
            margin: 0;
        }

        button.danger {
            color: #b00020;
        }

        .box {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 12px 0;
            background: #fafafa;
        }
    </style>
</head>

<body>
    <?= $content ?>
</body>

</html>