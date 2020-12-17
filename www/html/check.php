<?php
session_start();
require_once 'pdo_connect.php';
require_once 'function.php';

// セッションに値が無い場合
if (!isset($_SESSION['join'])) {
	header('Location: register.php');
	exit();
}

// パスワードの文字数分「＊」を表示
$password = $_SESSION['join']['password'];
$password_hide = str_repeat('*', strlen($password));


// 登録ボタン押されたら次の処理へ
if (isset($_POST['register'])) {

	try {
		$dbh->beginTransaction();
		//パスワードのハッシュ化
		$password_hash =  password_hash($_SESSION['join']['password'], PASSWORD_DEFAULT);

		$stmt = $dbh->prepare('INSERT INTO members SET name=?, email=?, password=?, picture=?, created=NOW()');
		$stmt->execute(array(
			$_SESSION['join']['name'],
			$_SESSION['join']['mail'],
			$password_hash,
			$_SESSION['image']
		));
		//セッション削除
		$_SESSION = array();

		$dbh->commit();
		// ログイン画面へリダイレクト
		header('Location: index.php?after_register');
		exit();
	} catch (PDOException $e) {
		$dbh->rollBack();
		$error = '<div class="alert alert-primary" role="alert"> 登録に失敗しました。もう一度お願いします。</div>';
		echo $e->getMessage();
		exit();
	}
	//データベース接続切断
	$dbh = null;
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>登録内容確認</title>
	<!-- ファビコン -->
	<link rel="shortcut icon" href="images/favicon.png" type="image/vnd.microsoft.icon">
	<link rel="icon" href="images/favicon.png" type="image/vnd.microsoft.icon">

	<!-- Bootstrap CSSの読み込み -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

	<!-- <link rel="stylesheet" href="css/style.css"> -->
</head>

<body>
	<!------- Header ------->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
		<div class="container">
			<a class="navbar-brand" href="#">WooJob</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarResponsive">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item active">
						<a class="nav-link" href="#">Home
							<span class="sr-only">(current)</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">About</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Services</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Contact</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>



	<!-- ログインフォーム -->
	<div class="container py-5" id="contact">
		<!-- 登録失敗エラー表示 -->
		<?= $error ?>

		<h2 class="mb-4">登録内容確認</h2>
		<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>



		<form action="" method="post">
			<input type="hidden" name="action" value="submit">

			<dl>
				<dt>ニックネーム</dt>
				<?= h($_SESSION['join']['name']); ?>
				<dd>
				</dd>
				<dt>メールアドレス</dt>
				<?= h($_SESSION['join']['mail']); ?>
				<dd>
				</dd>
				<dt>パスワード</dt>
				<dd>
					<?= $password_hide ?>
				</dd>
				<dt>写真など</dt>
				<dd>
					<?php if (!empty($_SESSION['image'])) : ?>
						<img src="images/member_picture/<?= (h($_SESSION['image'])); ?>" style="width:200px;">
					<?php endif; ?>
					<?php if ($_SESSION['Ext'] == 'error') : ?>
						<p class="text-danger">*「.gif」「.png」「.jpg」「.jpeg」の写真を使用してください</p>
					<?php endif; ?>
				</dd>
			</dl>


			<div class="mt-4"><a href="register.php" class="btn btn-light btn-lg mr-2">書き直す</a><input type="submit" name="register" class="btn btn-secondary btn-lg" value="登録する">
			</div>
		</form>

	</div>


	<!-- Optional JavaScript -->
	<!-- jQuery first, Popper.js, Bootstrap JSの順番に読み込む -->
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>