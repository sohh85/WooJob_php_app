<?php
session_start();
require_once 'pdo_connect.php';
require_once 'function.php';

// エラーに使用する変数をグローバルスコープに定義
$errors = [];

// postの値が無かったらsessionの値代入
// $name = isset($_POST['name']) ? $_POST['name'] : $_SESSION['join']['name'];
// $password = isset($_POST['password']) ? $_POST['password'] : $_SESSION['join']['password'];
// $mail = isset($_POST['mail']) ? $_POST['mail'] : $_SESSION['join']['mail'];
$name = $_POST['name'];
$password = $_POST['password'];
$mail = $_POST['mail'];
// 前後にある半角全角スペースを削除
$name = spaceTrim($name);
$password = spaceTrim($password);
$mail = spaceTrim($mail);
//アップロードを許可する拡張子
$cfg['ALLOW_EXTS'] = array('jpg', 'jpeg', 'png', 'gif');
$fileName = $_FILES['image']['name'];
// 画像拡張子に関するセッションを削除
unset($_SESSION['Ext']);


// 内容確認ボタンが押されたら次の処理へ
if (isset($_POST['check'])) {
    // 名前・メール・パスワードをバリデーション
    checkName($name);
    checkPwd($password);
    checkMail($mail);

    if (empty($errors)) { // エラーなければ次の処理へ
        // 表示できない拡張子 or 選択されなかった場合のデフォルト画像 。NULLでDB保存させない
        $image = 'human.png';
        if ($_FILES['image']['name']) { // 画像選択済み + 指定の拡張子 = 保存
            if (checkExt($fileName)) {
                $image = date('YmdHis') . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], 'images/member_picture/' . $image);
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
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>新規登録</title>
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

            <form action="" method="post" enctype="multipart/form-data">

                <div class="form-item">
                    <p class="formLabel">User Name</p>
                    <input type="text" name="name" class="form-style" value="<?= h($name); ?>">
                    <?= $errors['name']; ?>
                </div>

                <div class="form-item">
                    <p class="formLabel">Email</p>
                    <input type="email" name="mail" class="form-style" value="<?= h($mail); ?>">
                    <?= $errors['mail']; ?>
                </div>

                <div class="form-item">
                    <p class="formLabel">Password</p>
                    <input type="password" name="password" class="form-style" value="<?= h($password); ?>">
                    <!-- <div class="pw-view"><i class="fa fa-eye"></i></div> -->
                    <?= $errors['password']; ?>
                </div>

                <!-- <div class="form-item">
                    <label for="Image">
                        <span class="btn">
                            画像を選択<input type="file" name="image" id="Image" style="display:none">
                        </span>
                    </label>
                    <input type="text" readonly="">
                    <?= $errors['image']; ?>
                </div> -->

                <div class="form-item">
                    <p class="pull-left"><a href="index.php"><small>Log In</small></a></p>
                    <input name="check" type="submit" class="login pull-right" value="Confirm">
                    <div class="clear-fix"></div>
                </div>

            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.1.0.min.js"></script>
    <script src="js/form.js"></script>
    <!-- 画像名表示のためのJS -->
    <script src="js/show-file-name.js"></script>
</body>

</html>