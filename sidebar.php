<div class="bg-gray-200 w-1/4 p-4">
    <h2 class="text-xl font-bold mb-4">カテゴリー</h2>
    <ul class="list-disc pl-5 mb-4">
        <?php
        $categories = ['category1', 'category2', 'category3', 'category4', 'category5'];
        foreach ($categories as $category) {
            echo '<li><a href="index.php?category=' . urlencode($category) . '" class="text-blue-500 hover:underline">' . htmlspecialchars($category) . '</a></li>';
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
