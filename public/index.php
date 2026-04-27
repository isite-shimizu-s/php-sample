<?php

declare(strict_types=1);

header('Content-Type: text/html; charset=UTF-8');

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/src/posts.php'; // posts.phpはまだ関数なので残す

session_start();
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}
$csrf = (string)$_SESSION['csrf'];

$errors = [];

// POST処理（作成・削除・更新）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string)($_POST['action'] ?? '');

    $postedCsrf = (string)($_POST['csrf'] ?? '');
    if (!hash_equals($csrf, $postedCsrf)) {
        http_response_code(400);
        echo 'Bad Request (CSRF)';
        exit;
    }

    if ($action === 'create') {
        $name = trim((string)($_POST['name'] ?? ''));
        $message = trim((string)($_POST['message'] ?? ''));

        if ($name === '') {
            $errors[] = '名前を入力してください';
        }
        if ($message === '') {
            $errors[] = 'メッセージを入力してください';
        }

        if (!$errors) {
            posts_create($name, $message);
            header('Location: /');
            exit;
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            posts_delete($id);
        }
        header('Location: /');
        exit;
    } elseif ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim((string)($_POST['name'] ?? ''));
        $message = trim((string)($_POST['message'] ?? ''));

        if ($id <= 0) {
            $errors[] = 'IDが不正です';
        }
        if ($name === '') {
            $errors[] = '名前を入力してください';
        }
        if ($message === '') {
            $errors[] = 'メッセージを入力してください';
        }

        if (!$errors) {
            posts_update($id, $name, $message);
            header('Location: /');
            exit;
        }

        // エラー時は edit 画面に落とすためにGET相当の状態を作る
        $_GET['edit'] = (string)$id;
    }
}

// 画面表示（GET: 通常 or 編集）
$editingId = (int)($_GET['edit'] ?? 0);

ob_start();

if ($editingId > 0) {
    $post = posts_find($editingId);
    if (!$post) {
        echo '<p>対象の投稿が見つかりません。</p><p><a href="/">戻る</a></p>';
    } else {
        // 編集ビュー
        include dirname(__DIR__) . '/views/edit.php';
    }
} else {
    $posts = posts_list(50);
    include dirname(__DIR__) . '/views/index.php';
}

$content = ob_get_clean();
$title = 'Mini BBS (SQLite)';

include dirname(__DIR__) . '/views/layout.php';
