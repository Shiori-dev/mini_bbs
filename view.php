<!-- 投稿内容の個別表示画面 -->
<?php
//セッション開始
session_start();

//外部ファイル読み込み
require('dbconnect.php');
include('template/view.html');

//idが空の場合の処理
if(empty($_REQUEST['id'])){
  //強制的にmain.phpへ遷移
  header('Location: main.php');
  exit();
}
//DBから表示させたいメッセージを取得
$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
//URLパラメータから取得されたidを使ってメッセージを1件表示
$posts->execute(array($_REQUEST['id']));

//メッセージが表示されたときの処理 画像、名前、時間を取得して表示
if($post = $posts->fetch()){
  print(htmlspecialchars($post['picture']));
  print(htmlspecialchars($post['message']));
  print(htmlspecialchars($post['name']));
  print(htmlspecialchars($post['created']));
}else{
  echo 'その投稿は削除されたか、URLが間違っています';
}
