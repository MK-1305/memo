<?php
require('dbconnect.php');

// 最大ページ数を求める
$counts = $db->query('select count(*) as cnt from memos');
$count = $counts->fetch_assoc();
$max_page = floor(($count['cnt']+1)/5 +1);
// <!-- floor((件数-1) / 5 + 1) 1ページ5件ずつ表示するための式を求める -->

$stmt = $db->prepare('select * from memos order by id desc limit ?,5');
if (!$stmt) {
    die($db->error);
}
$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
// if (!$page) {
//     $page = 1;
// }
$page = ($page ?: 1);
// 三項演算子で上のif文を短く書ける
$start = ($page - 1) * 5;
// ページネーションで次の5件を表示させる式を$startに代入
$stmt->bind_param('i', $start);
$result = $stmt->execute();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メモ帳</title>
</head>
<body>
    <h1>メモ帳</h1>
    <?php if (!$result): ?>
        <p>表示できるメモがありません</p>
    <?php endif; ?>
    <p>→<a href="input.html">新しいメモ</a></p>
    <?php $stmt->bind_result($id, $memo, $created); ?>
    <?php while ($stmt->fetch()): ?>
        <div>
            <h2><a href="memo.php?id=<?php echo $id; ?>">
            <!-- メモの詳細ページに遷移できるようにidを取得する -->
            <?php echo htmlspecialchars(mb_substr($memo,0, 50));?></a></h2>
            <!-- メモの文字を全部表示するのではなく0~50文字までを表示させる -->
            <time><?php echo htmlspecialchars($created);?></time>
            <!-- 時間表示 -->
        </div>
        <hr>
    <?php endwhile ?>

    <p>
        <?php echo $page; ?>
        <?php if ($page>1): ?>
            <a href="?page=<?php echo $page = $page-1; ?>"><?php echo $page-1; ?>ページ目へ</a> |
        <?php endif ?>
        <?php if ($page<$max_page): ?>
            <a href="?page=<?php echo $page = $page+1; ?>"><?php echo  $page+1; ?>ページ目へ</a>
        <?php endif ?>
    </p>
</body>
</html>