<?php
//セッション開始
session_start();
//外部ファイル読み込み
include('template/logout.html');

//セッションの情報を削除するため配列は空欄
$_SESSION = array();
  //Cookieの設定ファイル
  if(ini_set('session.use_cookies')){
    //セッションで使ったCookieの情報を削除
    $params = session_get_cookie_params();
    //Cookieの有効期限を指定
    setcookie(session_name() . '', time() - 42000,
        $params['path'], $params['domain'],$params['secure'], $params['httponly']);
  }
  //セッションを完全に削除
session_destroy();
//Cookieに保存されたメールアドレスを削除(有効期限を指定)
setcookie('email', '', time()-3600);

//ログアウト後、ログイン画面へ遷移
header('Location: login.php');
exit();
?>
