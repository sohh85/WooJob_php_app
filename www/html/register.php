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
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>新規登録</title>
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
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Login</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Register<span class="sr-only">(current)</span></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <!-- 会員登録フォーム -->
    <div class="container" id="contact">

        <h2 class="mb-4">登録する</h2>
        <form action="" method="post" enctype="multipart/form-data">

            <div class="form-group">
                <label for="Name">ニックネーム</label>
                <input type="text" class="form-control col-md-9" name="name" id="Name" value="<?= h($name); ?>">
                <!-- エラー表示 -->
                <?= $errors['name']; ?>
            </div>


            <div class="form-group">
                <label for="Email">メールアドレス</label>
                <input type="email" class="form-control col-md-9" name="mail" id="Email" value="<?= h($mail); ?>" aria-describedby="emailHelp">
                <!-- エラー表示 -->
                <?= $errors['mail']; ?>
            </div>


            <div class="form-group">
                <label for="Password">パスワード</label>
                <input type="password" name="password" class="form-control col-md-9" id="Password" value="<?= h($password); ?>">
                <!-- エラー表示 -->
                <?= $errors['password']; ?>
            </div>

            <div class="input-group my-4">
                <label class="input-group-btn" for="Image">
                    <span class="btn btn-primary">
                        画像を選択<input type="file" name="image" id="Image" style="display:none">
                    </span>
                </label>
                <input type="text" class="form-control col-md-5" readonly="">
                <!-- エラー表示 -->
                <?= $errors['image']; ?>
            </div>

            <input class="btn btn-secondary btn-lg mt-3" type="submit" name="check" value="入力内容を確認">

        </form>

    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, Popper.js, Bootstrap JSの順番に読み込む -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script>
        $(document).on('change', ':file', function() {
            var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.parent().parent().next(':text').val(label);
        });
    </script>
</body>

</html>