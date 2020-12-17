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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ログイン</title>
    <!-- ファビコン -->
    <link rel="shortcut icon" href="images/favicon.png" type="image/vnd.microsoft.icon">
    <link rel="icon" href="images/favicon.png" type="image/vnd.microsoft.icon">


    <!-- Bootstrap CSSの読み込み -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/82342a278b.js" crossorigin="anonymous"></script>
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
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Login<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>



    <!-- ログインフォーム -->
    <div class="container" id="contact">

        <!-- アラート表示 -->
        <?php if (isset($_GET['logout'])) : ?>
            <div class="alert alert-primary mb-4 col-md-9" role="alert">ログアウトしました</div>
        <?php endif; ?>
        <?php if (isset($_GET['after_register'])) : ?>
            <div class="alert alert-primary mb-4 col-md-9" role="alert">登録ありがとうございます<br>下記フォームよりログインしてください</div>
        <?php endif; ?>

        <h2 class="mb-4">ログインする</h2>
        <form action="" method="post">

            <div class="form-group">
                <label for="Email">メールアドレス</label>
                <input type="email" class="form-control col-md-9" name="mail" id="Email" value="<?= h($mail); ?>" aria-describedby="emailHelp">
                <!-- エラー表示 -->
                <?= $errors['mail']; ?>
            </div>

            <div class="form-group">
                <label for="Password">パスワード</label>
                <input type="password" name="password" class="form-control col-md-9" id="Password" value="<?= h($_POST['password']); ?>">
                <!-- エラー表示 -->
                <?= $errors['password']; ?>
            </div>

            <div class="mt-4">
                <input id="save" type="checkbox" name="save" value="on" style="transform:scale(1.5);">
                <label for="save" class="pl-1">ログイン情報を記録する</label>
            </div>
            <input class="btn btn-secondary btn-lg mt-3" name="login" type="submit" value="ログインする">

        </form>


    </div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, Popper.js, Bootstrap JSの順番に読み込む -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>