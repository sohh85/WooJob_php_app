<?php
session_start();
require_once '/pdo_connect.php';
require_once 'function.php';


if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
  $_SESSION['time'] = time();

  $members = $dbh->prepare("SELECT * FROM members WHERE id=?");
  $members->execute(array($_SESSION['id']));
  $member = $members->fetch();
} else {
  header("location: login.php");
  exit();
}


if (!empty($_POST)) {
  if ($_POST['message'] !== "") {
    $message = $dbh->prepare("INSERT INTO posts SET member_id=?, message=?, created=NOW()");
    $message->execute(array(
      $member["id"],
      $_POST["message"],
    ));
    // var_dump($message->errorInfo()); //デバッグ
    // exit();
    header('Location: index.php');
    exit();
  }
}

$posts = $dbh->query("SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC");



?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>ひとこと掲示板</title>

  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>ひとこと掲示板</h1>
    </div>
    <div id="content">
      <div style="text-align: right"><a href="logout.php">ログアウト</a></div>
      <form action="" method="post">
        <dl>
          <dt><?php print(h($member["name"])); ?>さん、メッセージをどうぞ</dt>
          <dd>
            <textarea name="message" cols="50" rows="5"></textarea>
            <input type="hidden" name="reply_post_id" value="" />
          </dd>
        </dl>
        <div>
          <p>
            <input type="submit" value="投稿する" />
          </p>
        </div>
      </form>

      <?php foreach ($posts as $post) : ?>
        <div class="msg">
          <img src="/../member_picture/<?php print(h($post['picture'])); ?>" width="48" height="48" alt="<?php print(h($post['name'])); ?>" />
          <p><?php print(h($post['message'])); ?><span class="name">（<?php print(h($post['name'])); ?>）</span>[<a href="index.php?res=<?php print(h($post['id'])); ?>">Re</a>]</p>
          <p class="day"><a href="view.php?id="><?php print(h($post['created'])); ?></a>
            <a href="view.php?id=">
              返信元のメッセージ</a>
            [<a href="delete.php?id=" style="color: #F33;">削除</a>]
          </p>
        </div>
      <?php endforeach; ?>

      <ul class="paging">
        <li><a href="index.php?page=">前のページへ</a></li>
        <li><a href="index.php?page=">次のページへ</a></li>
      </ul>
    </div>
  </div>
</body>

</html>