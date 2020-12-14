<?php
session_start();

// ログアウトボタンが押された後の処理
if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    session_destroy();
}

if (isset($_SESSION['user'])) {
    $mail = $_SESSION['user']['mail'];
}

require_once '../pdo_connect.php';
require_once '../function.php';

require_once 'twitter_login.php'; // twitter apiの設定読み込み

// エラーに使用する変数をグローバルスコープに定義
$errors_password;
$errors_mail;

// ログインボタンが押されたら次の処理へ
if (isset($_POST['login'])) {
    $mail = $_POST['mail'];

    if (checkEmail($mail)) {   //メール形式確認
        $user = getUserByEmail($mail);  //登録済みメールか確認
        if (empty($user)) {
            $errors_mail = '<p class="text-danger">*登録されていないメールアドレスです</p>';
        } elseif (verifyPassword($user)) {
            $_SESSION['user'] = $user;   //ユーザ情報を配列でセッションに格納
            header('Location: ../index.php');   //トップページにリダイレクト
            exit();
        }
    }

    if (empty($_POST['password'])) {
        $errors_password = '<p class="text-danger">*パスワードは必須項目です</p>';
    }
}


// メール形式チェック。
function checkEmail($mail)
{
    if (empty($mail)) {
        $GLOBALS['errors_mail'] = '<p class="text-danger">*メールアドレスは必須項目です</p>';
        return false;
    }

    if (!preg_match("/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/", $mail)) {
        $GLOBALS['errors_mail'] = '<p class="text-danger">*メールアドレスは正しい形式で入力してください</p>';
        return false;
    }
    return true;
}

// 本登録テーブルからデータ取得
function getUserByEmail($mail)
{
    $stmt = $GLOBALS['dbh']->prepare('SELECT * FROM users WHERE mail = :mail');
    $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// パスワードチェック
function verifyPassword($user)
{
    if (password_verify($_POST['password'], $user['password']) == false) {
        $GLOBALS['errors_password'] = '<p class="text-danger">*パスワードが間違えています</p>';
        return false;
    }
    return true;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>ログイン -Study English Site for Engineers-</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css?family=Fredericka+the+Great&display=swap" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/user.css">
    <link rel="stylesheet" href="../styles/top.css">
</head>

<body>
    <div class="wrapper border border-secondary">

        <div class="head border-bottom border-secondary">
            <h1>ログインする</h1>
        </div>

        <div class="form-wrapper">

            <?php if (isset($_GET['logout'])) : ?>
                <div class="alert alert-primary mb-3" role="alert">ログアウトしました</div>
            <?php endif; ?>
            <?php if (isset($_GET['after_register'])) : ?>
                <div class="alert alert-primary mb-3" role="alert">登録ありがとうございます<br>下記フォームよりログインしてください</div>
            <?php endif; ?>

            <p>メールアドレスとパスワードを記入してログインしてください。<br>
                入会手続きがまだの方はこちらからどうぞ。</p>
            <p>&raquo;<a href="regist_mail.php">入会手続きをする</a></p>

            <form action="" method="post">
                <div class="form-group">
                    <label for="mail" class="m-0 small">メールアドレス <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="mail" name="mail" value="<?= h($mail); ?>">
                    <?= $errors_mail; ?>
                </div>

                <div class="form-group mb-4">
                    <label for="password" class="m-0 small">パスワード <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="password" name="password" value="<?= h($_POST['password']); ?>">
                    <?= $errors_password; ?>
                </div>

                <input type="submit" name="login" value="Log in" class="btn btn-warning btn-block confirm-btn">
            </form>

            <hr class="my-3">

            <!-- 横並びボタン  mdサイズより横幅が大きい場合ここを表示 -->
            <div class="d-none d-md-block">
                <div class="d-flex justify-content-between">
                    <!-- Twitterログイン -->
                    <a href="<?php echo $oauthUrl; ?>" class="btn btn-outline-info btn-lg sns">
                        <i class="fab fa-twitter white"></i> Log in with Twitter</a>
                    <!-- Facebookでログイン -->
                    <a href="FBLogin.php" class="btn btn-outline-primary btn-lg sns"> Log in with Facebook</a>
                </div>
            </div>
            <!--縦並びボタン  mdサイズより横幅が小さい場合ここを表示-->
            <div class="d-block d-md-none">
                <div class="d-flex flex-column">
                    <!-- Twitterログイン -->
                    <a href="<?php echo $oauthUrl; ?>" class="btn btn-outline-info btn-lg sns">
                        <i class="fab fa-twitter white"></i> Log in with Twitter</a>
                    <!-- Facebookでログイン -->
                    <a href="FBLogin.php" class="btn btn-outline-primary btn-lg sns"> Log in with Facebook</a>
                </div>
            </div>

            <hr class="my-3">

            <a href="../index.php" class="btn btn-danger btn-block return-btn">戻る</a>
        </div>
    </div>
    <?php include("../footer.php"); ?>
</body>

</html>