<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

function posts_list(int $limit = 50): array
{
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id, name, message, created_at FROM posts ORDER BY id DESC LIMIT :limit');
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function posts_create(string $name, string $message): void
{
    $pdo = db();
    $stmt = $pdo->prepare(
        'INSERT INTO posts (name, message, created_at) VALUES (:name, :message, :created_at)'
    );
    $stmt->execute([
        ':name' => $name,
        ':message' => $message,
        ':created_at' => date('Y-m-d H:i:s'),
    ]);
}

function posts_delete(int $id): void
{
    $pdo = db();
    $stmt = $pdo->prepare('DELETE FROM posts WHERE id = :id');
    $stmt->execute([':id' => $id]);
}

function posts_find(int $id): ?array
{
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id, name, message FROM posts WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}

function posts_update(int $id, string $name, string $message): void
{
    $pdo = db();
    $stmt = $pdo->prepare('UPDATE posts SET name = :name, message = :message WHERE id = :id');
    $stmt->execute([
        ':id' => $id,
        ':name' => $name,
        ':message' => $message,
    ]);
}
