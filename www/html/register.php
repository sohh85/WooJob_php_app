<?php
session_start();
require_once 'pdo_connect.php';
require_once 'function.php';

// エラーに使用する変数をグローバルスコープに定義
$errors = [];

// postの値が無かったらsessionの値代入
$name = isset($_POST['name']) ? $_POST['name'] : $_SESSION['join']['name'];
$password = isset($_POST['password']) ? $_POST['password'] : $_SESSION['join']['password'];
$mail = isset($_POST['mail']) ? $_POST['mail'] : $_SESSION['join']['mail'];
// 前後にある半角全角スペースを削除
$name = spaceTrim($name);
$password = spaceTrim($password);
$mail = spaceTrim($mail);
//アップロードを許可する拡張子
$cfg['ALLOW_EXTS'] = array('jpg', 'jpeg', 'png', 'gif');
$fileName = $_FILES['image']['name'];
$image = 0; // NULLが格納されないように
unset($_SESSION['Ext']); // 画像拡張子に関するセッションを削除

// 内容確認ボタンが押されたら次の処理へ
if (isset($_POST['check'])) {
    // 名前・メール・パスワードをバリデーション
    checkName($name);
    checkPwd($password);
    checkMail($mail);

    if (empty($errors)) { // エラーなければ確認ページへ

        if ($_FILES['image']['name']) { // 画像選択済み + 指定の拡張子 = 保存
            if (checkExt($fileName)) {
                $image = date('YmdHis') . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], 'member_picture/' . $image);
            } else {
                $_SESSION['Ext'] = 'error';
            }
        }

        $_SESSION['image'] = $image;
        $_SESSION['join'] = $_POST;
        header('Location: check.php');
        exit();
    }
}


// ---------------------------------------------------------ユーザネーム
function checkName($name)
{
    global $errors;

    if ($name == '') {
        $errors['name'] = '<p class="text-danger">* ユーザーネームが入力されていません</p>';
    } elseif (mb_strlen($name) > 20) {
        $errors['name'] = '<p class="text-danger">*ユーザーネームは20文字以内で入力して下さい</p>';
    }
    return true;
}

// ----------------------------------------------------------パスワード
function checkPwd($password)
{
    global $errors;

    if ($password == '') {
        $errors['password'] = '<p class="text-danger">* パスワードが入力されていません</p>';
    } elseif (!preg_match('/^[0-9a-zA-Z]{5,20}$/', $password)) {
        $errors['password'] = '<p class="text-danger">* パスワードは半角英数字を使用し、5文字以上20文字以下で入力して下さい</p>';
    }
    return true;
}

// -----------------------------------------------------------メール
function checkMail($mail) // メールの入力形式チェック。
{
    global $errors;

    if ($mail == '') {
        $errors['mail'] = '<p class="text-danger">* メールアドレスは必須項目です</p>';
    } elseif (!preg_match("/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/", $mail)) {
        $errors['mail'] = '<p class="text-danger">* メールアドレスは正しい形式で入力してください</p>';
    } else {
        $stmt = $GLOBALS['dbh']->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=:mail');
        $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
        $stmt->execute();
        $record = $stmt->fetch();

        if ($record['cnt'] > 0) {
            $errors['mail'] = '<p class="text-danger">* 既に登録されたメールアドレスです</p>';
        }
    }
    return true;
}

// --------------------------------------------------------------画像
function getExt($filename) //ファイル名から拡張子を取得
{
    return pathinfo($filename, PATHINFO_EXTENSION);
}

function checkExt($filename) //アップロードされたファイルの拡張子が許可されているかチェック
{
    global $cfg;
    $ext = strtolower(getExt($filename)); // strtolower関数で大文字の場合は小文字に変換
    return in_array($ext, $cfg['ALLOW_EXTS'], true);
}

// --------------------------------------------------------------その他
function spaceTrim($str) // 前後にある半角全角スペースを削除する関数
{
    $str = preg_replace('/^[ ]+/u', '', $str); // 行頭
    $str = preg_replace('/[ ]+$/u', '', $str); // 末尾
    return $str;
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
                <h1>登録する</h1>
            </div>
            <div id="content">
                <p>次のフォームに必要事項をご記入ください。</p>
                <p>既に登録済の方はこちらからどうぞ。</p>
                <p class="mb-3">&raquo;<a href="index.php">ログインする</a></p>

                <form action="" method="post" enctype="multipart/form-data">
                    <dl>

                        <dt>ニックネーム<span class="required">必須</span></dt>
                        <dd>
                            <input class="check_user" type="text" name="name" value="<?= h($name); ?>">
                            <?= $errors['name']; ?>
                        </dd>

                        <dt>メールアドレス<span class="required">必須</span></dt>
                        <dd>
                            <input class="check_user" type="text" name="mail" value="<?= h($mail); ?>">
                            <?= $errors['mail']; ?>
                        </dd>

                        <dt>パスワード<span class="required">必須</span></dt>
                        <dd>
                            <input class="check_user" type="password" name="password" value="<?= h($password); ?>">
                            <?= $errors['password']; ?>
                        </dd>

                        <dt>写真など</dt>
                        <dd>
                            <input type="file" name="image" value="">
                            <?= $errors['image'];  ?>
                        </dd>

                    </dl>
                    <input type="submit" name="check" value="入力内容を確認する">

                </form>
                <footer class="footer_bottom">
                    <p>Copyright - 赤坂 壮, 2020 All Rights Reserved.</p>
                </footer>
            </div>
        </div>
    </div>
</body>

</html>