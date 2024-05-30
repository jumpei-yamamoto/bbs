<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = htmlspecialchars($_POST['category']);
    $threadName = htmlspecialchars($_POST['thread_name']);
    $directory = 'threads/' . urlencode($category);
    $filePath = $directory . '/' . urlencode($threadName) . '.txt';

    // カテゴリフォルダが存在しない場合は作成
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    // 新しいスレッドファイルを作成
    if (!file_exists($filePath)) {
        file_put_contents($filePath, '');
    }

    header('Location: index.php?category=' . urlencode($category));
    exit();
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo 'Method Not Allowed';
    exit();
}
?>
