<?php
session_start();
require('dbconnect.php');

//ログインしているユーザーのメッセージが選択されているか確認
if(isset($_SESSION['id'])){
  $id = $_REQUEST['id'];
  //DBから削除する候補のメッセージを取得
  $messages = $db -> prepare('SELECT * FROM posts WHERE id=?'); //URLパラメータから取得したidを取得してSQLを走らせる
  $messages->execute(array($id)); //id=?にURLパラメータからわたされたidをわたす
  $message = $messages->fetch();  //データを取得


  if($message['member_id'] == $_SESSION['id']){  //DBから取得したmember_idとセッションに記録されたidが一致していたら削除できる
      $del = $db->prepare('DELETE FROM posts WHERE id=?');  //削除するSQL
      $del->execute(array($id));  //DBのデータを削除
  }
}
// 正しく削除が行われたらindex.phpに遷移
header('Location: index.php');
exit();
?>
