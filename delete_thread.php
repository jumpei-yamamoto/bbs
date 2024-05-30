<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = htmlspecialchars($_POST['category']);
    $threadName = htmlspecialchars($_POST['thread_name']);
    $filePath = 'threads/' . urlencode($category) . '/' . urlencode($threadName) . '.txt';

    if (file_exists($filePath)) {
        unlink($filePath);
    }

    header('Location: index.php?category=' . urlencode($category));
    exit();
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo 'Method Not Allowed';
    exit();
}
?>
