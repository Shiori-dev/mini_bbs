<?php
//エラー表示
ini_set( 'display_errors', 1 );
// error_reporting(E_ALL & ~E_NOTICE);
//セッション開始
session_start();
//dbconnect.php読み込み(DB接続)
require('dbconnect.php');
//function.php読み込み(htmlspecialchars)
require('app/functions.php');

//ページリクエストがGETの場合の処理(最初の読み込み時)
if($_SERVER['REQUEST_METHOD'] == 'GET'){
		//join.htmlを読み込み
		include 'template/join.html';
}//ページリクエストがPOSTの場合の処理(button押下時)
elseif($_SERVER['REQUEST_METHOD'] == 'POST'){
		//POSTされたJSON文字列を取り出し
		$json = file_get_contents("php://input");
		//JSON文字列をobjectに変換
		$contents = json_decode($json,true);
		//エラーメッセージ初期化
		$message['name'] ='';
		$message['email'] = '';
		$message['password'] = '';
		$message['image'] = '';

		// var_dump($contents['name']);
		// ニックネームの記入漏れチェック
	if($contents['name'] ===''){
		$error['name'] = 'blank';
	}	//ニックネームの文字数チェック
	elseif(($contents['name'] != '' && mb_strlen($contents['name']) < 3)){
		$error['name'] = 'length';
	}elseif(mb_strlen($contents['name']) >12){
		$error['name'] = 'length';
	}

	//メールアドレスの記入漏れチェック
	//メールアドレスが空だった場合の処理
	if($contents['email'] ===''){
		$error['email'] = 'blank';
	}//値が入っていたら以下のアカウント重複チェックを実行
	else{
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
		$member->execute(array($contents['email']));
		$record = $member->fetch();
			if($record['cnt'] > 0){
				//エラメッセージにduplicateを設定
				$error['email'] = 'duplicate';
			}
	}

	//パスワードの記入漏れチェック
	if($contents['password'] ===''){
		$error['password'] = 'blank';
	}//パスワードの文字数チェック
	elseif($contents['password'] != '' && (strlen($contents['password']) < 4 ))
	{
		$error['password'] = 'length';
	}

	// var_dump($contents);

	// 画像ファイルのエラーチェック
	// 画像がアップロードされていた場合、下記を実行
	if(!empty($_FILES['image'])){
		$ext = substr($$_FILES['image'], -3);
		//ファイルの拡張子チェック
		if($ext !='jpg' && $ext != 'jpeg' && $ext != 'gif' && $ext != 'png'){
		$error['image'] ='type';
		}
	}

	//ファイルアップロード
	if(empty($error)){
		//日付を付加したファイル名を作成し$imageに代入
		$image = date('YmdHis') . $_FILES['image']['name'];
		//保存する場所を指定してファイルをアップロード
		move_uploaded_file($_FILES['image']['tmp_name'],'member_picture/' . $image);
		//DBに保管するためセションjoinに値を保存
		$_SESSION['join'] = $contents;
		$_SESSION['join']['image'] = $image;
		//記入内容に問題がないとき、check.phpへ遷移
		header('Location: check.php');
	}

	//入力フォームのエラー文表示
	// ニックネームが記入されていない場合
		if(isset($error['name']) && $error['name'] == 'blank'){
				$message['name']= 'ニックネームを入力してください';
		}//ニックネームの文字数が規定外の場合
		elseif(isset($error['name']) && $error['name'] == 'length'){
				$message['name']= 'ニックネームは3~12文字で記入してください';
		}

		// メールアドレスが記入されていない場合
		if(isset($error['email']) && $error['email'] == 'blank'){
				$message['email'] = 'メールアドレスを入力してください';
		}// 登録済みのアドレスだった場合
		elseif(isset($error['email']) && $error['email'] == 'duplicate'){
				$message['email'] = '指定されたメールアドレスは、すでに登録されています';
		}

		//パスワードが4文字より少なかった場合
		if(isset($error['password']) && $error['password'] == 'length'){
				$message['password'] = 'パスワードは4文字以上で入力してください';
		}// パスワード記入漏れの場合
		elseif(isset($error['password']) && $error['password'] == 'blank'){
				$message['password'] =  'パスワードを入力してください';
		}

		//画像ファイルの拡張子エラー文
		if(isset($error['image']) && $error['image'] =='type'){
				$message['image'] = '「.gif」または「.jpg」または「.png」の画像を指定してください';
		}

		$ary_message = array(
			"name" => $message['name'],
			"email" => $message['email'],
			"password" => $message['password'],
			"image" => $message['image']
		);


		//messageをJASON形式で書き出し
		echo json_encode($ary_message,JSON_UNESCAPED_UNICODE);

		//URLパラメータにrewriteがあれば、$contentsに$_SESSIONの内容を代入(check.phpから戻ってきた場合)
		// if($_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])){
		// 	$contents = $_SESSION['join'];
		// }
}
