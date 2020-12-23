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
    if (checkPassword() && checkMail($mail)) {
        $user = getUserByMail($mail);
        if (empty($user)) {
            $errors['mail'] = '<p class="text-danger">*登録されていないメールアドレスです</p>';
        } elseif (verifyPassword($user)) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['time'] = time();
            header('Location: bulletin_board/index.php');
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
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ログイン</title>
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
            <form action="" method="post">

                <div class="form-item">
                    <p class="formLabel">Email</p>
                    <input type="email" name="mail" class="form-style" value="<?= h($mail); ?>">
                    <?= $errors['mail']; ?>
                </div>

                <div class="form-item">
                    <p class="formLabel">Password</p>
                    <input type="password" name="password" class="form-style" value="<?= h($_POST['password']); ?>">
                    <!-- <div class="pw-view"><i class="fa fa-eye"></i></div> -->
                    <?= $errors['password']; ?>
                </div>

                <div class="form-item font-gray">
                    <input id="save" type="checkbox" name="save" value="on">
                    <label for="save">ログイン情報を記録する</label>
                </div>

                <div class="form-item">
                    <p class="pull-left"><a href="register.php"><small>Register</small></a></p>
                    <input name="login" type="submit" class="login pull-right" value="Log In">
                    <div class="clear-fix"></div>
                </div>
            </form>

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.1.0.min.js"></script>
    <script src="js/form.js"></script>
</body>

</html>