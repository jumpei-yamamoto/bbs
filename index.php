<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>掲示板</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .break-words {
            word-break: break-word;
            white-space: pre-wrap; /* 改行をそのまま表示 */
        }
    </style>
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
            $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

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
                echo '<p class="text-red-500">カテゴリが存在しません。</p>';
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

            <?php
            if ($searchQuery) {
                echo '<hr class="my-6">';
                echo '<h2 class="text-xl font-bold mb-4">検索結果</h2>';
                echo '<ul class="list-disc pl-5 mb-4">';

                $resultsFound = false;
                
                foreach ($categories as $category) {
                    $categoryName = basename($category);
                    $threads = glob('threads/' . urlencode($categoryName) . '/*.txt');
                    foreach ($threads as $thread) {
                        $threadName = basename($thread, '.txt');
                        $lines = file($thread, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        foreach ($lines as $line) {
                            list($date, $name, $message) = explode(',', $line);
                            if (stripos($message, $searchQuery) !== false) {
                                echo '<li><a href="thread.php?category=' . urlencode($categoryName) . '&thread=' . urlencode($threadName) . '" class="text-blue-500 hover:underline">スレッド: ' . htmlspecialchars($threadName) . ' - ' . htmlspecialchars($message) . '</a></li>';
                                $resultsFound = true;
                                break;
                            }
                        }
                    }
                }

                if (!$resultsFound) {
                    echo '<p class="text-red-500">該当スレッドが見つかりませんでした。</p>';
                }

                echo '</ul>';
            }
            ?>
        </div>
    </div>
</body>
</html>
