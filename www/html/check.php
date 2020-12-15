<?php
session_start();
require_once 'pdo_connect.php';
require_once 'function.php';

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
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>会員登録</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="../css/style.css">
	<link rel="shortcut icon" href="images/favicon.png" type="image/vnd.microsoft.icon">
	<link rel="icon" href="images/favicon.png" type="image/vnd.microsoft.icon">
</head>

<body>
	<div id="wrap">
		<!-- header読み込み -->
		<?php include("header.php"); ?>
		<div class="content">
			<div id="head">
				<h1>会員登録</h1>
			</div>

			<div id="content">
				<?= $error ?>
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
								<img src="member_picture/<?= (h($_SESSION['image'])); ?>" style="width:200px;">
							<?php endif; ?>
						</dd>
					</dl>
					<div><a href="register.php">&laquo;&nbsp;書き直す</a> | <input type="submit" name='register' value="登録する">
					</div>
				</form>
			</div>
			<footer class="footer_bottom">
				<p>Copyright - 赤坂 壮, 2020 All Rights Reserved.</p>
			</footer>
		</div>
	</div>
</body>

</html>