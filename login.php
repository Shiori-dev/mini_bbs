<!-- ログイン画面 -->
<?php
//セッション開始
session_start();
//外部ファイルを読み込み
require('dbconnect.php');
include 'template/login.html';

//cookieにメールアドレスが保存されていたら$emailに代入
if($_COOKIE['email'] !==''){
  $email = $_COOKIE['email'];
}

//メールアドレスとパスワードが記入されたら、DBに接続して記入内容をSELECTして参照
if(!empty($_POST)){
  if($_POST['email'] !== '' && $_POST['password'] !==''){
    $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=? ');

//
  $login->execute(array(
    $_POST['email'],
    sha1($_POST['password'])
  ));
  $member = $login->fetch();

//
  if($member){
    $_SESSION['id'] = $member['id'];
    //timeというキーにログインをした時刻を代入
    $_SESSION['time'] = time();

    if($_POST['save'] === 'on'){
      setcookie('email', $_POST['email'], time()+60*60*24*14);
}

//ログインに成功したらmain.phpに遷移
  header('Location: main.php');
    exit();
    }else{
      $error['login'] = 'failed';
    }
  }else{
    $error['login'] = 'blank';
  }
}


//エラーメッセージの表示

//メールアドレスもしくはパスワードが空欄だった場合の処理
if($error['login'] === 'blank'){
  echo "*メールアドレスとパスワードをご記入ください";
}

if($error['login'] === 'failed'){
  echo "*ログインに失敗しました。正しくご記入ください";
}
