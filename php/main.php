<?php
session_start();
require('dbconnect.php');

//セッションに記録された時間から、1時間何もしない状態で自動的にログアウト
if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()){

  //ログイン中の処理
  //最新の操作時に現在の時刻をセッションのtimeに上書き
  $_SESSION['time'] = time();

  //ログイン後、DBから会員idを付与
  $members = $db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));
  //membersから変数memberにfetchで引き出したデータを保存
  $member = $members->fetch();

}else{
  //ログインをしていないときダイレクトのアクセスが有った場合、ログイン画面に遷移
  header('Location: ../login.html');
  exit();
}

//投稿するボタンクリック時の処理
if(!empty($_POST)){
  //$_POST['message'] に記載有りの場合
  if($_POST['message'] !== ''){
    //変数messageに対してインサート処理を実施
      $message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, reply_message_id=?, created=NOW()');
      $message->execute(array(
        $member['id'],
        $_POST['message'],
        $_POST['reply_post_id']
      ));

      //POSTの処理を行った後、main.htmlを再表示(ページを再読み込み時、メッセージを重複して登録するのを防ぐため)
      header('Location: ../main.html');
      exit();
  }
}

//ページネーション
//$pageに_REQUEST['page']を代入
$page = $_REQUEST['page'];
//パラメータのpageに1より小さい数を入れられた時に1ページ目を表示
if($page == ''){
  $page = 1;
}
$page = max($page,1);

//最終ページの取得
//SQLでメッセージの件数を取得
$counts = $db->query('SELECT COUNT(*) AS cnt FROM posts');
$cnt = $counts->fetch();
//最終のページ数を$maxPageへ代入(DBから取得した件数を5で割った数字を少数になったときに切り上げる)
$maxPage = ceil($cnt['cnt'] / 5);
//$maxPageで取得された数字以上の数字を指定された際、最終ページに補正して表示
$page = min($page, $maxPage);

// 5件ずつページネーションするため、$startに式を代入(URLパラメータで受け取った数字($page)に-1,そこに*5をする)
$start = ($page - 1) * 5;

//一覧表示するため投稿したメッセージを取得(DBから投稿された日が新しい順で取得)
//5件ずつ表示する(LIMIT ?,5)
$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m,posts p WHERE m.id=p.member_id ORDER BY p.created DESC LIMIT ?,5');
$posts->bindParam(1, $start, PDO::PARAM_INT);
$posts->execute();

//メッセージ返信の処理
if(isset($_REQUEST['res'])){
  //membersとpostsからデータを取得
  $response = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id =?');
  //変数response(「p.id」)に対してURLパラメータの数字を指定
  $response->execute(array($_REQUEST['res']));

  $table = $response->fetch();
  //＠をつけて名前とメッセージを出力
  $message = '@' . $table['name'] . ' ' . $table['message'];
}
?>
