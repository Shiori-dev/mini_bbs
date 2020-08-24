<?php
session_start();
require('dbconnect.php');

//idが空の場合の処理
if(empty($_REQUEST['id'])){
  //強制的にトップページへ遷移
  header('Location: ../main.html');
  exit();
}
//DBから表示させたいメッセージを取得
$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
//URLパラメータから取得されたidを使ってメッセージを1件表示
$posts->execute(array($_REQUEST['id']));
?>
