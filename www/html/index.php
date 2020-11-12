<?php
session_start();
require_once 'pdo_connect.php';
require_once 'function.php';


if ($_COOKIE['email'] !== '') {
  $email = $_COOKIE['email'];
}

if (!empty($_POST)) {
  $email = $_POST['email'];

  if ($_POST['email'] !== '' && $_POST['password'] !== '') {
    $login = $dbh->prepare('SELECT * FROM members WHERE email=? AND password=?');
    $login->execute(array(
      $_POST['email'],
      sha1($_POST['password'])
    ));
    $member = $login->fetch();

    if ($member) {
      $_SESSION['id'] = $member['id'];
      $_SESSION['time'] = time();

      if ($_POST['save'] === 'on') {
        setcookie('email', $_POST['email'], time() + 60 * 60 * 24 * 4);
      }
      header('Location: after_login/index.php');
      exit();
    } else {
      $error['login'] = 'failed';
    }
  } else {
    $error['login'] = 'blank';
  }
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Log in</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
  <div id="wrap">
    <!-- header読み込み -->
    <?php include("header.php"); ?>
    <div class="content">
      <div id="head">
        <h1>Log in</h1>
      </div>
      <div id="content">
        <div id="lead">
          <p>メールアドレスとパスワードを記入してログインしてください。</p>
          <p>入会手続きがまだの方はこちらからどうぞ。</p>
          <p>&raquo;<a href="register.php">入会手続きをする</a></p>
        </div>
        <form action="" method="post">
          <dl>
            <dt>メールアドレス</dt>
            <dd>

              <input type="text" name="email" size="35" maxlength="255" value="<?= h($email); ?>" />
              <?php if ($error['login'] === 'blank') : ?>
                <P class="error">*メールアドレスとパスワードを入力してください</P>
              <?php endif; ?>
              <?php if ($error['login'] === 'failed') : ?>
                <P class="error">*ログインに失敗しました。正しく入力してください</P>
              <?php endif; ?>
            </dd>
            <dt>パスワード</dt>
            <dd>
              <input type="password" name="password" size="35" maxlength="255" value="<?php print h($_POST['password']); ?>" />
            </dd>
            <dt>ログイン情報の記録</dt>
            <dd>
              <input id="save" type="checkbox" name="save" value="on">
              <label for="save">次回からは自動的にログインする</label>
            </dd>
          </dl>
          <div>
            <input type="submit" value="ログインする" />
          </div>
        </form>
        <footer class="footer_bottom">
          <p>Copyright - 赤坂 壮, 2020 All Rights Reserved.</p>
        </footer>
      </div>
    </div>
  </div>
</body>

</html>