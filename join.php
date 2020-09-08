<?php
//エラー表示
ini_set( 'display_errors', 1 );
error_reporting(E_ALL & ~E_NOTICE);
//セッション開始
session_start();
//dbconnect.php読み込み(DB接続)
require('dbconnect.php');
//function.php読み込み(htmlspecialchars)
require('app/functions.php');
//join.php読み込み
include 'template/join.html';

//フォーム送信時、$POSTで受け取った値が空ではない場合実行するエラーチェックを設定
if(!empty($_POST)){
// ニックネームの記入漏れチェック
if($_POST['name'] ===''){
$error['name'] = 'blank';
}
//ニックネームの文字数チェック
if(strlen($_POST['name']) < 2 && ($_POST['name']) > 12  ){
$error['name'] = 'length';
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
//画像がアップロードされていた場合、下記を実行
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
//日付を付加したファイル名を作成し$imageに代入
$image = date('YmdHis') . $_FILES['image']['name'];
//保存する場所を指定してファイルをアップロード
move_uploaded_file($_FILES['image']['tmp_name'],'member_picture/' . $image);
//DBに保管するためセションjoinに値を保存
$_SESSION['join'] = $_POST;
$_SESSION['join']['image'] = $image;
//記入内容に問題がないとき、check.phpへ遷移
header('Location: check.php');
exit();
}
}

//入力フォームのエラー文表示
//ニックネームが記入されていない場合
if($error['name'] === 'blank'){
$message['name']= 'ニックネームを入力してください';
exit();
}

//ニックネームの文字数が規定外の場合
if($error['name'] === 'length'){
$message['name']= 'ニックネームは3~12文字で記入してください';
exit();
}

//メールアドレスが記入されていない場合
if($error['email'] === 'blank'){
$message['email'] = 'メールアドレスを入力してください';
exit();
}

//登録済みのアドレスだった場合
if($error['email'] === 'duplicate'){
$message['email'] = '指定されたメールアドレスは、すでに登録されています';
exit();
}

//パスワードが4文字より少なかった場合
if($error['password'] === 'length'){
$message['password'] = 'パスワードは4文字以上で入力してください';
exit();
}

//パスワード記入漏れの場合
if($error['password'] === 'blank'){
$message['password'] = 'パスワードを入力してください';
exit();

}

//画像ファイルの拡張子エラー文
if($error['image'] ==='type'){
$message['image'] = '「.gif」または「.jpg」または「.png」の画像を指定してください';
exit();
}
return $message;


//messageをJASON形式で書き出し
print json_encode($message);


//URLパラメータにrewriteがあれば、$_POSTに$_SESSIONの内容を代入(check.phpから戻ってきた場合)
if($_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])){
	$_POST = $_SESSION['join'];
}





// // //フォーム送信時、$_POSTで受け取った値が空ではない場合実行するエラーチェックを設定
// if(!empty($_POST)){
// 	// ニックネームの記入漏れチェック
// 	if($_POST['name'] ===''){
// 		$error['name'] = 'blank';
// 	}
// 		//ニックネームの文字数チェック
// 		if(strlen($_POST['name']) < 2 && ($_POST['name']) > 12  ){
// 			$error['name'] = 'length';
// 		}
// 	//メールアドレスの記入漏れチェック
// 	if($_POST['email'] ===''){
// 		$error['email'] = 'blank';
// 	}
// 	//パスワードの文字数チェック
// 	if(strlen($_POST['password']) < 4 ){
// 		$error['password'] = 'length';
// 	}
// 	//パスワードの記入漏れチェック
// 	if($_POST['password'] ===''){
// 		$error['password'] = 'blank';
// 	}
// 	//画像がアップロードされていた場合、下記を実行
// 	//画像ファイルのエラーチェック
// 	$fileName = $_FILES['image']['name'];
// 	if(!empty($fileName)){
// 		$ext = substr($fileName, -3);
// 		//ファイルの拡張子チェック
// 		if($ext !='jpg' && $ext != 'jpeg' && $ext != 'gif' && $ext != 'png'){
// 			$error['image'] ='type';
// 		}
// 	}

// 	//アカウントの重複をチェック
// 	if(empty($error)){
// 		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
// 		$member->execute(array($_POST['email']));
// 		$record = $member->fetch();
// 		if($record['cnt'] > 0){
// 			//エラメッセージにduplicateを設定
// 			$error['email'] = 'duplicate';
// 		}
// 	}

// 	var_dump($_POST['name']);
// 	var_dump($_POST['email']);
// 	var_dump($_POST['password']);
// 	var_dump($_POST['image']);

// //ファイルアップロード
// 	if(empty($error)){
// 		//日付を付加したファイル名を作成し$imageに代入
// 		$image = date('YmdHis') . $_FILES['image']['name'];
// 		//保存する場所を指定してファイルをアップロード
// 		move_uploaded_file($_FILES['image']['tmp_name'],'member_picture/' . $image);
// 		//DBに保管するためセションjoinに値を保存
// 		$_SESSION['join'] = $_POST;
// 		$_SESSION['join']['image'] = $image;
// 		//記入内容に問題がないとき、check.phpへ遷移
// 		header('Location: check.php');
// 		exit();
// 	}
// }


// //入力フォームのエラー文表示
// //ニックネームが記入されていない場合
// if($error['name'] === 'blank'){
// 	$error[0]= json_encode('ニックネームを入力してください', JSON_UNESCAPED_UNICODE);
// 	exit();
// }

// //ニックネームの文字数が規定外の場合
// if($error['name'] === 'length'){
// 	$error[0]= json_encode('ニックネームは3~12文字で記入してください', JSON_UNESCAPED_UNICODE);
// 	exit();
// }

// //メールアドレスが記入されていない場合
// if($error['email'] === 'blank'){
// 	$error[1] = json_encode('メールアドレスを入力してください');
// 	exit();
// }

// //登録済みのアドレスだった場合
// if($error['email'] === 'duplicate'){
// 	$error[1] = json_encode('指定されたメールアドレスは、すでに登録されています');
// 	exit();
// }

// //パスワードが4文字より少なかった場合
// if($error['password'] === 'length'){
// 	$error[2] = json_encode('パスワードは4文字以上で入力してください');
// 	exit();
// }

// //パスワード記入漏れの場合
// if($error['password'] === 'blank'){
// 	$error[2] = json_encode('パスワードを入力してください', JSON_UNESCAPED_UNICODE);
// 	exit();
// }

// //画像ファイルの拡張子エラー文
// if($error['image'] ==='type'){
// 	$error[3] = json_encode('「.gif」または「.jpg」または「.png」の画像を指定してください');
// 	exit();
// }
