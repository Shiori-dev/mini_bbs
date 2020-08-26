<!-- 登録内容確認ページ -->
<?php
//
ini_set('display_errors', 1);

//セッション開始
session_start();

//外部ファイル読み込み
require('dbconnect.php');
include('template/check.html');

//セッションの内容を検査(入力画面を正しく通過せずにcheck.phpが呼び出された場合)
if(!isset($_SESSION['join'])){
	//強制的に会員登録画面へ遷移
	header('Location: join.php');
	exit();
}

//
if(!empty($_POST)){
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, picture=?, created=NOW()');
$statement->execute(array(
	$_SESSION['join']['name'],
	$_SESSION['join']['email'],
	sha1($_SESSION['join']['password']),
	$_SESSION['join']['image']
));

//
unset($_SESSION['join']);

//
header('Location: thanks.');
exit();
}

//登録画面で記入されたニックネームを表示
print(htmlspecialchars($_SESSION['join']['name'],ENT_QUOTES));

//登録画面で記入されたメールアドレスを表示
print(htmlspecialchars($_SESSION['join']['email'],ENT_QUOTES));

//登録画面でアップされた画像を表示
if($_SESSION['join']['image'] !== ''){
	print(htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES));
}
