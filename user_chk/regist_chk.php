<?php
session_start();

require_once '../function.php';
require_once '../pdo_connect.php';


$password = $_SESSION['password'];
$password_hide = str_repeat('*', strlen($password));
$urltoken = $_SESSION['urltoken'];

$mail = $_SESSION['mail'];
$name = $_SESSION['name'];
$error;

// 登録ボタン押されたら次の処理へ
if (isset($_POST['submit_info'])) {

    try {
        $dbh->beginTransaction();
        //パスワードのハッシュ化
        $password_hash =  password_hash($_SESSION['password'], PASSWORD_DEFAULT);
        //usersテーブルに本登録
        $stmt = $dbh->prepare("INSERT INTO users (name,mail,password) VALUES (:name,:mail,:password_hash)");
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
        $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
        $stmt->execute();

        pre_usersのflagを1にする
        $stmt = $dbh->prepare("UPDATE pre_users SET flag=1 WHERE mail=(:mail)");
        $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
        $stmt->execute();

        //セッション削除
        $_SESSION = array();
        // session_destroy();

        $_SESSION['user']['mail'] = $mail;
        $dbh->commit();

        // ログイン画面へリダイレクト
        header("Location: login.php?after_register");
        exit();
    } catch (PDOException $e) {
        $dbh->rollBack();
        $error = "登録に失敗しました。もう一度お願いします。";
        echo $e->getMessage();
        exit();
    }
    //データベース接続切断
    $dbh = null;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>会員情報確認 -Study English Site for Engineers-</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css?family=Fredericka+the+Great&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../styles/user.css">
    <link rel="stylesheet" href="../styles/top.css">
</head>

<body>
    <div class="wrapper border border-secondary">

        <div class="head border-bottom border-secondary">
            <h1>会員登録確認画面</h1>
        </div>

        <div class="form-wrapper">
            <form action="" method="post">

                <ul class="list-group list-group-flush mb-5">
                    <li class="list-group-item"><span class="text-muted">Email</span> : <?= h($_SESSION['mail']); ?></li>
                    <li class="list-group-item"><span class="text-muted">Name</span> : <?= h($_SESSION['name']); ?></li>
                    <li class="list-group-item"><span class="text-muted">Password</span> : <?= $password_hide ?></li>
                </ul>

                <?php if (isset($error)) : ?>
                    <p class="text-danger"><?= $error ?></p>
                <?php endif; ?>

                <input type="submit" name="submit_info" value="登録する" class="btn btn-block btn-success">
                <hr class="my-3">
                <a href="regist.php?urltoken=<?= $urltoken ?>" class="btn btn-danger btn-block return-btn">戻る</a>
            </form>

        </div>
    </div>
    <?php include("../footer.php"); ?>
</body>

</html>