<?php
session_start();
require('dbconnect.php');

if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()){    //セッションに記録された時間から、1時間何もしない状態で自動的にログアウト
  //ログイン中の処理
  $_SESSION['time'] = time();   //最新の操作時に現在の時刻をセッションのtimeに上書き

  //ログインに成功したら、データベースからログインした会員idを引き出す
  $members = $db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));
  $member = $members->fetch();    //membersから変数memberにfetchで引き出したデータを保存

}else{
  //ログインをしていないときダイレクトのアクセスが有った場合ログイン画面に遷移させる
  header('Location: login.php');
  exit();
}

if(!empty($_POST)){   //$_POSTがあれは(投稿するボタンがクリックされた時)
  if($_POST['message'] !== ''){   //$_POST['message'] が空でなければ
      $message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, reply_message_id=?, created=NOW()');   //変数messageに対してインサート処理を行う
      $message->execute(array(
        $member['id'],   //ログイン時にデータベースから取得したidと同じ($_SESSION['id']より、より確実)
        $_POST['message'],
        $_POST['reply_post_id']  //返信対象のメッセージid
      ));

      //POSTの処理を行った後もう一度index.phpを呼び出す(ページを再読み込み時、メッセージを重複して登録するのを防ぐため)
      header('Location: index.php');
      exit();
  }
}

//一覧表示するため投稿したメッセージを取得(DBから投稿された日が新しい順で取得)
$posts = $db->query('SELECT m.name, m.picture, p.* FROM members m,posts p WHERE m.id=p.member_id ORDER BY p.created DESC');

//メッセージ返信の処理
if(isset($_REQUEST['res'])){
  $response = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id =?');  //membersとpostsからデータを取得
  $response->execute(array($_REQUEST['res']));  //変数response(「p.id」)に対してURLパラメータの数字を指定

  $table = $response->fetch();
  $message = '@' . $table['name'] . ' ' . $table['message'];
} //＠をつけて名前とメッセージを出力する
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ひとこと掲示板</title>

	<link rel="stylesheet" href="style.css" />
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>ひとこと掲示板</h1>
  </div>
  <div id="content">
    <div style="text-align: right"><a href="logout.php">ログアウト</a></div>
    <form action="" method="post">
      <dl>
      <!-- アカウントの名前を表示 -->
        <dt><?php print(htmlspecialchars($member['name'],ENT_QUOTES)); ?>さん、メッセージをどうぞ</dt>
        <dd>
          <!-- 返信したいメッセージを表示 -->
          <textarea name="message" cols="50" rows="5"><?php print(htmlspecialchars($message, ENT_QUOTES)); ?></textarea>
          <!-- 返信したいメッセージのidをフォームにわたす -->
          <input type="hidden" name="reply_post_id" value="<?php print(htmlspecialchars($_REQUEST['res'], ENT_QUOTES)); ?>" />
        </dd>
      </dl>
      <div>
        <p>
          <input type="submit" value="投稿する" />
        </p>
      </div>
    </form>

    <!-- 投稿の一覧を表示 -->
    <!-- 繰り返し配列の中身を精査して$postにわたす -->
    <?php foreach($posts as $post): ?>
    <div class="msg">
    <!-- アカウントが登録する画像を取得して表示 画像が保管されているディレクトリ名「member_picture/」を補完-->
    <img src="member_picture/<?php print(htmlspecialchars($post['picture'], ENT_QUOTES)); ?>" width="48" height="48" alt="<?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?>" />
    <!-- DBから1件取得した$POST['message']を表示する $post['name']で投稿者の名前を表示 -->
    <!-- 「RE」の押下時に返信したいユーザーのidをパラメータに付与する -->
    <p><?php print(htmlspecialchars($post['message'], ENT_QUOTES)); ?><span class="name">（<?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?>）</span>[<a href="index.php?res=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>">Re</a>]</p>

    <!-- 投稿日時を取得して表示 -->
    <p class="day"><a href="view.php?id=<?php print(htmlspecialchars($post['id'])); ?>"><?php print(htmlspecialchars($post['created'], ENT_QUOTES)); ?></a>

      <!-- 返信ではないメッセージには「返信元~」を表記しないif文 -->
      <?php if($post['reply_message_id'] > 0): ?>
    <!-- 「返信元のメッセージ」の選択でview.phpへ遷移 -->
    <a href="view.php?id=<?php print(htmlspecialchars($post['reply_message_id'], ENT_QUOTES)); ?>">返信元のメッセージ</a>
      <?php endif; ?>
    [<a href="delete.php?id="
    style="color: #F33;">削除</a>]
    </p>
    </div>
    <?php endforeach; ?>
    <!-- $postの繰り返し終わり -->


<ul class="paging">
<li><a href="index.php?page=">前のページへ</a></li>
<li><a href="index.php?page=">次のページへ</a></li>
</ul>
  </div>
</div>
</body>
</html>
