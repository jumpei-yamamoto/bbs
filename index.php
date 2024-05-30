<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>掲示板</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto flex">
        <!-- サイドバー -->
        <?php include 'sidebar.php'; ?>

        <!-- メインコンテンツ -->
        <div class="w-3/4 p-4">
            <h1 class="text-2xl font-bold mb-4">掲示板</h1>

            <?php
            $categories = array_filter(glob('threads/*'), 'is_dir');
            $selectedCategory = isset($_GET['category']) ? $_GET['category'] : (isset($categories[0]) ? basename($categories[0]) : '');

            if ($selectedCategory) {
                echo '<h2 class="text-xl font-bold mb-4">スレッド一覧 (' . htmlspecialchars($selectedCategory) . ')</h2>';
                echo '<ul class="list-disc pl-5 mb-4">';

                $threadDir = 'threads/' . urlencode($selectedCategory);
                if (!is_dir($threadDir)) {
                    mkdir($threadDir, 0777, true);
                }

                $threads = glob($threadDir . '/*.txt');
                foreach ($threads as $thread) {
                    $threadName = basename($thread, '.txt');
                    echo '<li class="flex items-center"><a href="thread.php?category=' . urlencode($selectedCategory) . '&thread=' . urlencode($threadName) . '" class="text-blue-500 hover:underline">' . htmlspecialchars($threadName) . '</a>
                          <form action="delete_thread.php" method="post" class="inline ml-2">
                              <input type="hidden" name="category" value="' . htmlspecialchars($selectedCategory) . '">
                              <input type="hidden" name="thread_name" value="' . htmlspecialchars($threadName) . '">
                              <button type="submit" class="text-red-500 hover:text-red-700 focus:outline-none">×</button>
                          </form></li>';
                }
                echo '</ul>';
            } else {
                echo '<p class="text-red-500">カテゴリが選択されていません。</p>';
            }
            ?>

            <hr class="my-6">

            <h2 class="text-xl font-bold mb-4">スレッドを作成</h2>
            <form action="create_thread.php" method="post" class="bg-white p-6 rounded shadow-md">
                <div class="mb-4">
                    <label for="thread_name" class="block text-gray-700">スレッド名：</label>
                    <input type="text" id="thread_name" name="thread_name" class="w-full px-3 py-2 border rounded" required>
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($selectedCategory); ?>">
                </div>
                <div class="text-right">
                    <input type="submit" value="作成" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
