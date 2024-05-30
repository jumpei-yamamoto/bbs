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
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">掲示板</h1>

        <?php
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        $threadName = isset($_GET['thread']) ? $_GET['thread'] : '';
        $filePath = 'threads/' . urlencode($category) . '/' . urlencode($threadName) . '.txt';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars($_POST['name']);
            $message = htmlspecialchars($_POST['message']);
            $message = str_replace("\n", "<br>", $message); // 改行を<br>に変換
            $date = date('Y-m-d H:i:s');
            
            $data = "$date,$name,$message\n";
            
            file_put_contents($filePath, $data, FILE_APPEND | LOCK_EX);
            
            header('Location: thread.php?category=' . urlencode($category) . '&thread=' . urlencode($threadName));
            exit();
        }

        function make_links($text) {
            return preg_replace(
                '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@',
                '<a href="$1" class="text-blue-500 hover:underline" target="_blank">$1</a>',
                $text
            );
        }
        ?>

        <form action="thread.php?category=<?php echo urlencode($category); ?>&thread=<?php echo urlencode($threadName); ?>" method="post" class="bg-white p-6 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-gray-700">名前：</label>
                <input type="text" id="name" name="name" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="message" class="block text-gray-700">メッセージ：</label>
                <textarea id="message" name="message" rows="4" class="w-full px-3 py-2 border rounded" required></textarea>
            </div>
            <div class="text-right">
                <input type="submit" value="投稿" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
            </div>
        </form>

        <hr class="my-6">
        <h2 class="text-xl font-bold mb-4">投稿一覧</h2>

        <?php
        if (file_exists($filePath)) {
            $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $table = <<<EOT
            <div class="bg-white p-6 rounded shadow-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">日時</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">名前</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">メッセージ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
            EOT;
            
            foreach ($lines as $line) {
                list($date, $name, $message) = explode(',', $line);
                $message = str_replace("<br>", "\n", $message); // <br>を改行に変換
                $message = nl2br(make_links($message)); // 改行を<br>に変換し、URLをリンクに変換
                $table .= <<<EOT
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{$date}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{$name}</td>
                    <td class="px-6 py-4 break-words">{$message}</td>
                </tr>
                EOT;
            }
            
            $table .= <<<EOT
                </tbody>
            </table>
            </div>
            EOT;
            
            echo $table;
        } else {
            echo '<div class="bg-white p-6 rounded shadow-md">投稿はまだありません。</div>';
        }
        ?>

        <div class="mt-6">
            <a href="index.php?category=<?php echo urlencode($category); ?>" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">スレッド一覧に戻る</a>
        </div>
    </div>
</body>
</html>
