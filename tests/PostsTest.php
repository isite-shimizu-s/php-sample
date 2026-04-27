<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/posts.php';

final class PostsTest extends TestCase
{
    protected function setUp(): void
    {
        // APP_DB は phpunit.xml で data/test.sqlite を指している前提
        require_once __DIR__ . '/../src/db.php';

        $pdo = db();

        // テーブルが無ければ作られているはずだが、念のため
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            message TEXT NOT NULL,
            created_at TEXT NOT NULL
        )'
        );

        // テストごとに初期化（重要）
        $pdo->exec('DELETE FROM posts');
        // IDも毎回リセットしたいなら（任意）
        $pdo->exec("DELETE FROM sqlite_sequence WHERE name='posts'");
    }

    public function testCreateAndList(): void
    {
        posts_create('alice', 'hello');

        $posts = posts_list(50);
        $this->assertCount(1, $posts);
        $this->assertSame('alice', $posts[0]['name']);
        $this->assertSame('hello', $posts[0]['message']);
    }

    public function testUpdate(): void
    {
        posts_create('alice', 'hello');
        $posts = posts_list(50);
        $id = (int)$posts[0]['id'];

        posts_update($id, 'bob', 'updated');

        $p = posts_find($id);
        $this->assertNotNull($p);
        $this->assertSame('bob', $p['name']);
        $this->assertSame('updated', $p['message']);
    }

    public function testDelete(): void
    {
        posts_create('alice', 'hello');
        $posts = posts_list(50);
        $id = (int)$posts[0]['id'];

        posts_delete($id);

        $posts2 = posts_list(50);
        $this->assertCount(0, $posts2);
    }
}
