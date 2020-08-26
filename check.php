<!-- 登録内容確認ページ -->
<?php
//
ini_set('display_errors', 1);

//セッション開始
session_start();

//外部ファイル読み込み
require('dbconnect.php');
require('app/functions.php');
include('template/check.html');

//セッションの内容を検査(入力画面を正しく通過せずにcheck.phpが呼び出された場合)
if(!isset($_SESSION['join'])){
	//強制的に会員登録画面へ遷移
	header('Location: join.php');
	exit();
}

//「登録する」ボタンが押されたとき$_POSTの内容が入っていたらDBに入力内容を登録
if(!empty($_POST)){
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, picture=?, created=NOW()');
//executeメソッドの値として一つずつ値を挿入
$statement->execute(array(
	$_SESSION['join']['name'],
	$_SESSION['join']['email'],
	//パスワードはそのまま記入せずsha1で暗号化
	sha1($_SESSION['join']['password']),
	$_SESSION['join']['image']
));
//登録完了後unsetでセッションの内容を削除(重複登録を避けるため)
unset($_SESSION['join']);
//完了画面へ遷移
header('Location: thanks.php');
//処理終了
exit();
}

//登録画面で記入されたニックネームを表示
print(h($_SESSION['join']['name']));

//登録画面で記入されたメールアドレスを表示
print(h($_SESSION['join']['email']));

//登録画面でアップされた画像を表示
if($_SESSION['join']['image'] !== ''){
	print(h($_SESSION['join']['image']));
}
