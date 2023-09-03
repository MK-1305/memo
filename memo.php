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
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INIT);
    $stmt->bind_param('i', $id);
    // bind_paramに値は入れれないので変数に代入してから変数を使う
    $stmt->execute();

    $stmt->bind_result($id, $memo, $created);
    // 受け取った値を何の変数に入れるかを決める
    $stmt->fetch();
    ?>
    <div><?php echo htmlspecialchars($memo); ?></div>
</body>
</html>