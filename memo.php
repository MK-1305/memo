<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メモ詳細</title>
</head>
<body>
    <?php 
    require('dbconnect.php');
    $stmt = $db->prepare('select * from memos where id=?');
    if (!$stmt){
        die($db->error);
    }
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if (!$id) {
        echo '表示するメモを指定してください';
        exit();
    }
    // 正しくパラメーターを渡さなかった場合やイタズラを防ぐ
    $stmt->bind_param('i', $id);
    // bind_paramに値は入れれないので変数に代入してから変数を使う
    $stmt->execute();
    // sqlを実行する

    $stmt->bind_result($id, $memo, $created);
    // 受け取った値を何の変数に入れるかを決める
    $result = $stmt->fetch();
    if (!$result) {
        echo '指定されたメモは見つかりませんでした';
        exit();
    }
    ?>
    <div><pre><?php echo htmlspecialchars($memo); ?></pre></div>

    <p><a href="update.php?id=<?php echo $id; ?>">編集する</a> |
        <a href="delete.php?id=<?php echo $id; ?>">削除する</a> |
        <a href="/memo">一覧へ</a></p>
</body>
</html>