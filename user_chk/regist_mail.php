<?php
session_start();

require_once '../pdo_connect.php';

$error;  // エラーに使用する変数をグローバルスコープに定義

//登録ボタンが押されたら次の処理へ
if (isset($_POST['submit_mail'])) {
    $mail = $_POST['mail'];

    if (checkEmail($mail)) {
        if (checkUserMail()) {
            if (checkPreStatus()) {

                // トークン付きURL生成
                $urltoken = hash('sha256', uniqid(rand(), 1));
                $url = "http://localhost:8000/user_chk/regist.php" . "?urltoken=" . $urltoken;

                //仮登録テーブルにデータ格納
                try {
                    $stmt = $dbh->prepare("INSERT INTO pre_users (urltoken,mail,date) VALUES (:urltoken,:mail,now() )");
                    $stmt->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
                    $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
                    $stmt->execute();
                    $dbh = null;
                } catch (PDOException $e) {
                    print('Error:' . $e->getMessage());
                    die();
                }

                /*  ----------------------  メール送信機能  ----------------------  */

                require '../../vendor/autoload.php';

                $dotenv = Dotenv\Dotenv::create(__DIR__);
                $dotenv->load();

                $email = new \SendGrid\Mail\Mail();

                $email->setFrom("ses.joint.development@gmail.com", "SE2 -Syudy English for Enginners-");
                $email->setSubject("【SE2】会員登録用URLのお知らせ");
                $email->addTo($mail);
                $email->addContent(
                    "text/html",
                    "<p>登録ありがとうございます<br>
                        24時間以内に下記URLからアクセスし、登録を行って下さい</p>
                        <a href='$url'>$url</a>"
                );

                $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
                $response = $sendgrid->send($email);

                /*  -------------------------  ここまで  -------------------------  */
                // セッション削除
                $_SESSION = array();
                session_destroy();
            }
        }
    }
}


// メールの入力形式チェック。
function checkEmail($mail)
{
    if (empty($mail)) {
        $GLOBALS['errors_email'] = "メールアドレスは必須項目です";
        return false;
    }

    if (!preg_match("/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/", $mail)) {
        $GLOBALS['errors_email'] = "メールアドレスは正しい形式で入力してください";
        return false;
    }
    return true;
}

// 本登録メール重複チェック
function checkUserMail()
{
    $stmt = $GLOBALS['dbh']->prepare('SELECT COUNT(*) AS cnt FROM users WHERE mail=:mail');
    $stmt->bindValue(':mail', $GLOBALS['mail'], PDO::PARAM_STR);
    $stmt->execute();
    $record = $stmt->fetch();

    if ($record['cnt'] > 0) {
        $GLOBALS['errors_email'] = "既に登録されたメールアドレスです";
        return false;
    }
    return true;
}


function checkPreStatus()
{
    $stmt = $GLOBALS['dbh']->prepare('SELECT * FROM pre_users WHERE mail=:mail');
    $stmt->bindValue(':mail', $GLOBALS['mail'], PDO::PARAM_STR);
    $stmt->execute();
    $check = $stmt->fetch();

    if ($check) {   //仮登録テーブルにメールアドレスが登録済なら次の処理へ
        $pre_date = $check['date'];   // 仮登録された日時を変数に格納

        // メールが送信されて24時間が過ぎている場合、仮登録テーブルから同一カラム削除
        if ($pre_date < date("Y-m-d H:i:s", strtotime("-1 day"))) {
            $stmt = $GLOBALS['dbh']->prepare('DELETE FROM pre_users WHERE mail=:mail');
            $stmt->bindValue(':mail', $GLOBALS['mail'], PDO::PARAM_STR);
            $stmt->execute();
            return false;
        }

        // メールが送信されて24時間以内の場合
        if ($pre_date > date("Y-m-d H:i:s", strtotime("-1 day"))) {
            $GLOBALS['error'] = "登録手続き中のメールアドレスです<br>* 送信済メールに添付されたリンクから登録を行ってください";
            return false;
        }
    }
    return true;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>メール登録 -Study English Site for Engineers-</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css?family=Fredericka+the+Great&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../styles/user.css">
    <link rel="stylesheet" href="../styles/top.css">
</head>

<body>
    <div class="wrapper border border-secondary">

        <div class="head border-bottom border-secondary">
            <h1>メール登録画面</h1>
        </div>

        <div class="form-wrapper">
            <form action="" method="post">

                <?php if (empty($error) && isset($_POST['submit_mail'])) : ?>
                    <p class="alert alert-primary" role="alert">メールをお送りしました。24時間以内にメールに記載されたURLからご登録下さい</p>
                <?php endif; ?>

                <div class="form-group">

                    <label for="mail" class="m-0 small">メールアドレス <span class="text-danger">*</span></label>
                    <input type="text" name="mail" id="mail" class="form-control">

                    <?php if (!empty($error) && isset($_POST['submit_mail'])) : ?>
                        <p class="text-danger">* <?= $error ?></p>
                    <?php endif; ?>

                </div>

                <input type="submit" name="submit_mail" value="登録する" class="btn btn-warning btn-block confirm-btn">
                <hr class="my-3">
                <a href="../index.php" class="btn btn-danger btn-block return-btn">戻る</a>
            </form>

        </div>
    </div>
    <?php include("../footer.php"); ?>
</body>

</html>