<?php
session_start();
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

	require_once 'pdo_connect.php';

	try {
		$dbh->beginTransaction();
		//パスワードをハッシュ化しDBに格納
		$password_hash =  password_hash($_SESSION['join']['password'], PASSWORD_DEFAULT);

		$stmt = $dbh->prepare('INSERT INTO members SET name=?, email=?, password=?, picture=?, created=NOW()');
		$stmt->execute(array(
			$_SESSION['join']['name'],
			$_SESSION['join']['mail'],
			$password_hash,
			$_SESSION['image']
		));

		// ユーザIDを取得
		$memberId = $dbh->lastInsertId();

		$_SESSION = array();
		$dbh->commit(); // 実行

		$_SESSION['id'] = $memberId;
		$_SESSION['time'] = time();
		header('Location: bulletin_board/index.php');
		exit();
	} catch (PDOException $e) {
		$dbh->rollBack();
		$error = '<div class="text-danger"> 登録に失敗しました。もう一度お願いします。</div>';
		echo $e->getMessage();
		exit();
	}
	$dbh = null;
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>登録内容確認</title>
	<!-- ファビコン -->
	<link rel="shortcut icon" href="images/favicon.png" type="image/vnd.microsoft.icon">
	<link rel="icon" href="images/favicon.png" type="image/vnd.microsoft.icon">
	<!-- CSSの読み込み -->
	<link rel="stylesheet" href="css/form.css">
</head>

<body>
	<div id="formWrapper">
		<div id="form">

			<div class="logo">
				<img src="images/favicon.png" class="logo-img" alt="WooJobタイトル画像">
			</div>
			<!-- 登録失敗エラー表示 -->
			<?= $error ?>
			<h2>登録内容確認</h2>
			<p class="form-guide">記入した内容が正しければ「登録する」ボタンをクリックしてください。</p>

			<form action="" method="post">
				<input type="hidden" name="action" value="submit">

				<dl>
					<dt>ニックネーム</dt>
					<dd>
						<?= h($_SESSION['join']['name']); ?>
					</dd>
					<dt>メールアドレス</dt>
					<dd>
						<?= h($_SESSION['join']['mail']); ?>
					</dd>
					<dt>パスワード</dt>
					<dd>
						<?= $password_hide ?>
					</dd>
					<!-- <dt>プロフィール画像</dt>
					<dd>
						<?php if (!empty($_SESSION['image'])) : ?>
							<img src="images/member_picture/<?= (h($_SESSION['image'])); ?>" style="width:200px;">
						<?php endif; ?>
						<?php if ($_SESSION['Ext'] == 'error') : ?>
							<p class="text-danger">*「.gif」「.png」「.jpg」「.jpeg」の写真を使用してください</p>
						<?php endif; ?>
					</dd> -->
				</dl>

				<div class="form-item">
					<p class="pull-left"><a href="register.php"><small>Rewrite</small></a></p>
					<input type="submit" name="register" class="login pull-right" value="Register">
					<div class="clear-fix"></div>
				</div>
			</form>

		</div>
	</div>
	<script src="https://code.jquery.com/jquery-2.1.0.min.js"></script>
	<script src="js/form.js"></script>
</body>

</html>