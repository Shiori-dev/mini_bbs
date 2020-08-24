<?php
session_start();
require('dbconnect.php');

if(!empty($_POST)){
	if($_POST['name'] ===''){
		$error['name'] = 'blank';
	}
	if($_POST['email'] ===''){
		$error['email'] = 'blank';
	}
	if(strlen($_POST['password']) < 4 ){
		$error['password'] = 'length';
	}
	if($_POST['password'] ===''){
		$error['password'] = 'blank';
	}
	$fileName = $_FILES['image']['name'];
	if(!empty($fileName)){
		$ext = substr($fileName, -3);
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
			$error['email'] = 'duplicate';
		}
	}

	if(empty($error)){
		$image = date('YmdHis') . $_FILES['image']['name'];
		move_uploaded_file($_FILES['image']['tmp_name'],'../member_picture/' . $image);
		$_SESSION['join'] = $_POST;
		$_SESSION['join']['image'] = $image;
		header('Location: ../check.html');
		exit();
	}
}

if($_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])){
		$_POST = $_SESSION['join'];
}

?>
