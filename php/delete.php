<?php
session_start();
require('dbconnect.php');

//ログインしているユーザーのメッセージが選択されているか確認
if(isset($_SESSION['id'])){
  $id = $_REQUEST['id'];
  //DBから削除する候補のメッセージを取得(URLパラメータから取得したidを取得してSQLを走らせる)
  $messages = $db -> prepare('SELECT * FROM posts WHERE id=?');
  //id=?にURLパラメータから渡されたidを付与
  $messages->execute(array($id));
  //データを取得
  $message = $messages->fetch();

//DBから取得したmember_idとセッションに記録されたidが一致していたら削除
  if($message['member_id'] == $_SESSION['id']){
      //削除するSQL
      $del = $db->prepare('DELETE FROM posts WHERE id=?');
      //DBのデータを削除
      $del->execute(array($id));
  }
}
// 正しく削除が行われたらmain.htmlに遷移
header('Location: ../main.html');
exit();
?>
