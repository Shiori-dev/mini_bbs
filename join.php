<!-- 会員登録画面 -->
<?php
//セッション開始
session_start();
//外部ファイル読み込み
require('dbconnect.php');
require('app/functions.php');
include 'template/join.html';

// //フォーム送信時、$_POSTが空ではない場合実行するエラーチェックを設定
if(!empty($_POST)){
	// ニックネームの記入漏れチェック
	if($_POST['name'] ===''){
		$error['name'] = 'blank';
	}
	//メールアドレスの記入漏れチェック
	if($_POST['email'] ===''){
		$error['email'] = 'blank';
	}
	//パスワードの文字数チェック
	if(strlen($_POST['password']) < 4 ){
		$error['password'] = 'length';
	}
	//パスワードの記入漏れチェック
	if($_POST['password'] ===''){
		$error['password'] = 'blank';
	}
	//画像ファイルのエラーチェック
	$fileName = $_FILES['image']['name'];
	if(!empty($fileName)){
		$ext = substr($fileName, -3);
		//ファイルの拡張子チェック
		if($ext !='jpg' && $ext != 'jpeg' && $ext != 'gif' && $ext != 'png'){
			$error['image'] ='type';
		}
	}


	//アカウントの重複をチェック
	if(empty($error)){
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
		$member->execute(array($_POST['email']));
		$record = $member->fetch();
		if($record['cnt'] > 0){
			//エラメッセージにduplicateを設定
			$error['email'] = 'duplicate';
		}
	}

//ファイルアップロード
	if(empty($error)){
		//日付を付加したファイル名を$imageに代入
		$image = date('YmdHis') . $_FILES['image']['name'];
		//保存する場所を指定してファイルをアップロード
		move_uploaded_file($_FILES['image']['tmp_name'],'member_picture/' . $image);
		//セションに値を保存
		$_SESSION['join'] = $_POST;
		$_SESSION['join']['image'] = $image;
		//記入内容に問題がないとき、check.phpへ遷移
		header('Location: check.php');
		exit();
	}
}

//URLパラメータにrewriteがあれば、$_POSTに$_SESSIONの内容を代入(check.phpから戻ってきた場合)
if($_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])){
		$_POST = $_SESSION['join'];
}

//入力フォームのエラー文表示
//ニックネームが記入されていない場合
if($error['name'] === 'blank'){
	echo 'ニックネームを入力してください';
	exit();
}

//メールアドレス
if($error['email'] === 'blank'){
		echo 'メールアドレスを入力してください';
	exit();
}

//登録済みのアドレスだった場合のエラー文表記のページに遷移
if($error['email'] === 'duplicate'){
	echo '指定されたメールアドレスは、すでに登録されています';
	exit();
}

//パスワードのエラー文表記のページに遷移
if($error['password'] === 'length'){
	echo 'パスワードは4文字以上で入力してください';
	exit();
}

//パスワード記入漏れのエラー文表記のページに遷移
if($error['password'] === 'blank'){
	echo 'パスワードを入力してください';
	exit();
}

//画像ファイルの拡張子エラー文表記のページに遷移
if($error['image'] ==='type'){
	echo '「.gif」または「.jpg」または「.png」の画像を指定してください';
	exit();
}
