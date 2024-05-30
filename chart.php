<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device幅=device-width, initial-scale=1.0">
    <title>投稿者数統計</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">投稿者数統計</h1>
        <button onclick="window.location.href='index.php'" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700 mb-4">掲示板に戻る</button>

        <div id="chartContainer">
            <canvas id="topPostersChart" width="400" height="200"></canvas>
        </div>

        <?php
        $postCounts = [];
        $categories = array_filter(glob('threads/*'), 'is_dir');

        // 各カテゴリのスレッドの投稿を読み込み、投稿者ごとに集計
        foreach ($categories as $category) {
            $categoryName = basename($category);
            $threads = glob('threads/' . urlencode($categoryName) . '/*.txt');
            foreach ($threads as $thread) {
                $lines = file($thread, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    list($date, $name, $message) = explode(',', $line);
                    if (isset($postCounts[$name])) {
                        $postCounts[$name]++;
                    } else {
                        $postCounts[$name] = 1;
                    }
                }
            }
        }

        arsort($postCounts);
        $topPosters = array_slice($postCounts, 0, 5, true);

        $names = json_encode(array_keys($topPosters));
        $counts = json_encode(array_values($topPosters));
        ?>

        <div id="chartData" data-names='<?php echo $names; ?>' data-counts='<?php echo $counts; ?>'></div>
        
        <script src="chart.js"></script>
    </div>
</body>
</html>
