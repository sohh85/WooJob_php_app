<?php
session_start();
require_once 'pdo_connect.php';
require_once 'function.php';

// エラーに使用する変数をグローバルスコープに定義
$errors = [];

if ($_COOKIE['mail'] !== '') {
  $mail = $_COOKIE['mail'];
}
// 自動ログインボタン押されたらクッキーにメール格納
if ($_POST['save'] === 'on') {
  setcookie('mail', $_POST['mail'], time() + 60 * 60 * 24 * 4);
}


// ログインボタンが押されたら次の処理へ
if (isset($_POST['login'])) {
  $mail = $_POST['mail'];

  if (checkPassword() && checkMail($mail)) {   //メール形式確認
    $user = getUserByMail($mail);  //登録済みメールか確認
    if (empty($user)) {
      $errors['mail'] = '<p class="text-danger">*登録されていないメールアドレスです</p>';
    } elseif (verifyPassword($user)) {
      $_SESSION['id'] = $user['id'];  //ユーザ情報を配列でセッションに格納
      $_SESSION['time'] = time();
      header('Location: after_login/index.php');   //トップページにリダイレクト
      exit();
    }
  }
}


// メール形式チェック。
function checkMail($mail)
{
  global $errors;

  if (empty($mail)) {
    $errors['mail'] = '<p class="text-danger">*メールアドレスは必須項目です</p>';
    return false;
  }

  if (!preg_match("/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/", $mail)) {
    $errors['mail'] = '<p class="text-danger">*メールアドレスは正しい形式で入力してください</p>';
    return false;
  }
  return true;
}

// 入力されたメールアドレス用いてデータ取得
function getUserByMail($mail)
{
  $stmt = $GLOBALS['dbh']->prepare('SELECT * FROM members WHERE email = :mail');
  $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

// パスワード形式チェック
function checkPassword()
{
  global $errors;

  if (empty($_POST['password'])) {
    $errors['password'] = '<p class="text-danger">*パスワードは必須項目です</p>';
  }
  return true;
}

// パスワードがDB内と一致してるかチェック
function verifyPassword($user)
{
  global $errors;

  if (password_verify($_POST['password'], $user['password']) == false) {
    $errors['password'] = '<p class="text-danger">*パスワードが間違えています</p>';
    return false;
  }
  return true;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="css/style.css">
  <link rel="shortcut icon" href="images/favicon.png" type="image/vnd.microsoft.icon">
  <link rel="icon" href="images/favicon.png" type="image/vnd.microsoft.icon">
</head>

<body>
  <div id="wrap">
    <!-- header読み込み -->
    <?php include("header.php"); ?>
    <div class="content">
      <div id="head">
        <h1>ログインする</h1>
      </div>
      <div id="content">

        <?php if (isset($_GET['logout'])) : ?>
          <div class="alert alert-primary" role="alert">ログアウトしました</div>
        <?php endif; ?>
        <?php if (isset($_GET['after_register'])) : ?>
          <div class="alert alert-primary" role="alert">登録ありがとうございます<br>下記フォームよりログインしてください</div>
        <?php endif; ?>

        <div id="lead">
          <p>メールアドレスとパスワードを記入してログインしてください。</p>
          <p>入会手続きがまだの方はこちらからどうぞ。</p>
          <p>&raquo;<a href="register.php">入会手続きをする</a></p>
        </div>
        <form action="" method="post">
          <dl>

            <dt>メールアドレス</dt>
            <dd>
              <input class="check_user" type="text" name="mail" size="35" maxlength="255" value="<?= h($mail); ?>">
              <?= $errors['mail']; ?>
            </dd>

            <dt>パスワード</dt>
            <dd>
              <input class="check_user" type="password" name="password" size="35" maxlength="255" value="<?= h($_POST['password']); ?>">
              <?= $errors['password']; ?>
            </dd>

            <dt>ログイン情報の記録</dt>
            <dd>
              <input id="save" type="checkbox" name="save" value="on">
              <label for="save">次回からは自動的にログインする</label>
            </dd>

          </dl>
          <div>
            <input class="check_user" name="login" type="submit" value="ログインする">
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