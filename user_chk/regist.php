<?php
session_start();

require_once '../function.php';
require_once '../pdo_connect.php';

// エラーに使用する変数をグローバルスコープに定義
// $errors_name;
// $errors_password;

// メールに添付されたURL(token付き)からのみアクセス可能。
// if (empty($_GET)) {
//     header("Location: regist_mail.php");
//     exit();
// }

$urltoken = isset($_GET['urltoken']) ? $_GET['urltoken'] : NULL;
// postの値が無かったらsessionの値代入
$name = isset($_POST['name']) ? $_POST['name'] : $_SESSION['name'];
$password = isset($_POST['password']) ? $_POST['password'] : $_SESSION['password'];
// 前後にある半角全角スペースを削除
$name = spaceTrim($name);
$password = spaceTrim($password);


// urlから取得したトークンを参照、条件を満たしているか確認
// if (empty($urltoken)) {
//     $error_url = "正規の手順で、もう一度登録を行ってください";
// } else {
//     $stmt = $dbh->prepare("SELECT mail FROM pre_users WHERE urltoken=(:urltoken) AND flag =0 AND date > now() - interval 24 hour");
//     $stmt->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
//     $stmt->execute();
//     $row_count = $stmt->rowCount();  // レコード件数取得

//     // 条件を満たしたユーザーの場合 ( 24時間以内に仮登録済 )
//     if ($row_count == 1) {
//         $mail_array = $stmt->fetch();
//         $mail = $mail_array['mail'];
//         $_SESSION['mail'] = $mail;
//     } else {
//         $error_url = "このURLは有効期限が過ぎているか、既に登録が完了しています。<br>もう一度登録をやりなおして下さい。";
//     }
// }

// submitボタンが押されたら、入力値のチェック
if (isset($_POST['submit_info'])) {
    if (nameCheck() && passwordCheck()) {
        // OKならセッションに値格納し、確認ページへ
        $_SESSION['name'] = $name;
        $_SESSION['password'] = $password;
        $_SESSION['urltoken'] = $urltoken;

        header("Location: regist_chk.php");
        exit();
    }
}

// 前後にある半角全角スペースを削除する関数
// function spaceTrim($str)
// {
//     // 行頭
//     $str = preg_replace('/^[ ]+/u', '', $str);
//     // 末尾
//     $str = preg_replace('/[ ]+$/u', '', $str);
//     return $str;
// }


// function nameCheck()
// {
//     if ($GLOBALS['name'] == '') :
//         $GLOBALS['error_name'] = '<p class="text-danger">* ユーザーネームが入力されていません</p>';
//     elseif (mb_strlen($GLOBALS['name']) > 20) :
//         $GLOBALS['error_name'] = '<p class="text-danger">*ユーザーネームは20文字以内で入力して下さい</p>';
//     endif;
//     return true;
// }


// function passwordCheck()
// {
//     if ($GLOBALS['password'] == '') :
//         $GLOBALS['error_password'] = '<p class="text-danger">* パスワードが入力されていません</p>';
//         return false;
//     elseif (!preg_match('/^[0-9a-zA-Z]{5,20}$/', $GLOBALS['password'])) :
//         $GLOBALS['error_password'] = '<p class="text-danger">* パスワードは半角英数字を使用し、5文字以上20文字以下で入力して下さい</p>';
//         return false;
//     endif;
//     return true;
// }


?>

<!DOCTYPE html>
<html>

<head>
    <title>会員情報入力 -Study English Site for Engineers-</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css?family=Fredericka+the+Great&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../styles/user.css">
    <link rel="stylesheet" href="../styles/top.css">
</head>

<body>
    <div class="wrapper border border-secondary">

        <div class="head border-bottom border-secondary">
            <h1>会員登録画面</h1>
        </div>

        <div class="form-wrapper">
            <?php if (empty($error_url)) : ?>

                <form action="" method="post">
                    <div class="form-group">
                        <p class="small m-0">メールアドレス <span class="text-danger">*</span></p>
                        <p><?= h($mail); ?></p>
                    </div>

                    <div class="form-group">
                        <label for="mail" class="small m-0">ユーザーネーム <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="<?= h($name); ?>">

                        <?php if (!empty($error_name) && isset($_POST['submit_info'])) : ?>
                            <p class="text-danger">* <?= $error_name ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mb-4">
                        <label for="mail" class="small m-0">パスワード <span class="text-danger">*</span></label>
                        <input type="text" name="password" class="form-control" value="<?= h($password); ?>">

                        <?php if (!empty($error_password) && isset($_POST['submit_info'])) : ?>
                            <p class="text-danger">* <?= $error_password ?></p>
                        <?php endif; ?>
                    </div>

                    <input type="submit" name="submit_info" value="確認する" class="btn btn-warning btn-block confirm-btn">
                </form>

            <?php else : ?>
                <p class="text-danger mb-0">* <?= $error_url ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php include("../footer.php"); ?>
</body>

</html>