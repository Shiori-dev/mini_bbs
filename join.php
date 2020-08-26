<!-- 会員登録画面 -->
<?php
//セッション開始
session_start();
//外部ファイル読み込み
require('dbconnect.php');
require('app/functions.php');
include 'template/join.html';

//フォームが送信時、$_POSTが空ではない場合エラーチェックを実行
if(!empty($_POST)){
	//ニックネームの記入漏れチェック
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
		if($ext !='jpg' && $ext != 'gif' && $ext != 'png'){
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
//ニックネームが記入されていない場合、エラー文を表示
if($error['name'] === 'blank'){
		echo '*ニックネームを入力してください';
}

//メールアドレス
//記入漏れのエラー文を表示
if($error['email'] === 'blank'){
	echo '*メールアドレスを入力してください';
}

//登録済みのアドレスだった場合のエラー文
if($error['email'] === 'duplicate'){
	echo '指定されたメールアドレスは、すでに登録されています';
}

//パスワードのエラー文
if($error['password'] === 'length'){
	echo '*パスワードは4文字以上で入力してください';
}

//パスワード記入漏れのエラー文を表示
if($error['password'] === 'blank'){
	echo '*パスワードを入力してください';
}

//画像ファイルの拡張子チェック
if($error['image'] ==='type'){
	echo '*「.gif」または「.jpg」または「.png」の画像を指定してください';
}

//必須項目の再登録が必要な場合、画像の再登録を依頼
if(!empty($error)){
echo '*恐れ入りますが、画像を改めて指定してください';
}
