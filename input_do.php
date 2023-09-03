<?php
$memo = filter_input(INPUT_POST, 'memo', FILTER_SANITIZE_SPECIAL_CHARS);
//filter_inputで危険な文字列を予め排除する 

require('dbconnect.php');
$stmt = $db->prepare('insert into memos(memo) values(?)');
if (!$stmt): 
    die($db->error);
endif;
$stmt->bind_param('s', $memo);
$ret = $stmt->execute();

if ($ret):
    echo '登録されました';
    echo '<br>→<a href="index.php">トップに戻る</a>';
else:
    echo $db->error;
endif;
?>