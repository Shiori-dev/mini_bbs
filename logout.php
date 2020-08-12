<?php
session_start();


$_SESSION = array();  //セッションの情報を削除するため空の配列を入れる
  if(ini_set('session.use_cookies')){ //Cookieの設定ファイル
    //セッションで使ったCookieの情報を削除する
    $params = session_get_cookie_params();
    setcookie(session_name() . '', time() - 42000,  //Cookieの有効期限を指定
        $params['path'], $params['domain'],$params['secure'], $params['httponly']);
  }
session_destroy();  //セッションを完全に消す
setcookie('email', '', time()-3600);  //Cookieに保存されたメールアドレスを削除(有効期限を指定)

header('Location: login.php');
exit();
?>
