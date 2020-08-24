<?php
ini_set('display_errors', 1);

session_start();
require('dbconnect.php');

if(!isset($_SESSION['join'])){
	header('Location: ../main.html');
	exit();
}
if(!empty($_POST)){
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, picture=?, created=NOW()');
$statement->execute(array(
	$_SESSION['join']['name'],
	$_SESSION['join']['email'],
	sha1($_SESSION['join']['password']),
	$_SESSION['join']['image']
));
unset($_SESSION['join']);

header('Location: ../thanks.html');
exit();
}
?>
