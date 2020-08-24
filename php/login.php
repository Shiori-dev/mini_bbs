<?php
session_start();
require('dbconnect.php');

if($_COOKIE['email'] !==''){
  $email = $_COOKIE['email'];
}

if(!empty($_POST)){
  if($_POST['email'] !== '' && $_POST['password'] !==''){
    $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=? ');

  $login->execute(array(
    $_POST['email'],
    sha1($_POST['password'])
  ));
  $member = $login->fetch();

  if($member){
    $_SESSION['id'] = $member['id'];
    //timeというキーにログインをした時刻を代入
    $_SESSION['time'] = time();

    if($_POST['save'] === 'on'){
      setcookie('email', $_POST['email'], time()+60*60*24*14);
}
    header('Location: ../main.html');
    exit();
    }else{
      $error['login'] = 'failed';
    }
  }else{
    $error['login'] = 'blank';
  }
}
?>
