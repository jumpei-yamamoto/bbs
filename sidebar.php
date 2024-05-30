<div class="bg-gray-200 w-1/4 p-4">
    <h2 class="text-xl font-bold mb-4">検索</h2>
    <form action="index.php" method="get">
        <input type="text" name="search" placeholder="検索キーワード" class="w-full px-3 py-2 border rounded mb-4">
        <input type="submit" value="検索" class="w-full px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
    </form>

    <h2 class="text-xl font-bold mb-4 mt-4">カテゴリー</h2>
    <ul class="list-disc pl-5 mb-4">
        <?php
        // カテゴリフォルダ一覧を取得
        $categories = array_filter(glob('threads/*'), 'is_dir');
        foreach ($categories as $category) {
            $categoryName = basename($category);
            echo '<li><a href="index.php?category=' . urlencode($categoryName) . '" class="text-blue-500 hover:underline">' . htmlspecialchars($categoryName) . '</a></li>';
        }
        ?>
    </ul>
    <h2 class="text-xl font-bold mb-4">投稿数が多い上位5名</h2>
    <button id="toggleChartButton" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700 mb-4">投稿者数統計</button>
</div>

<script>
    document.getElementById('toggleChartButton').addEventListener('click', function() {
        window.location.href = 'chart.php';
    });
</script>
