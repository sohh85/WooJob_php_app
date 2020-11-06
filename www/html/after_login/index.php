<?php
session_start();
require_once '../pdo_connect.php';
require_once '../function.php';


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
    $message = $dbh->prepare("INSERT INTO posts SET member_id=?, message=?, reply_message_id=?, created=NOW()");
    $message->execute(array(
      $member['id'],
      $_POST['message'],
      $_POST['reply_post_id'],
    ));
    // var_dump($message->errorInfo()); // デバッグ
    // exit();
    header('Location: index.php');
    exit();
  }
}


$page = $_REQUEST['page'];
if ($page == '') {
  $page = 1;
}
$page = max($page, 1); // 配列から一番大きな値を取り出す

$counts = $dbh->query('SELECT COUNT(*) AS cnt FROM posts');
$cnt = $counts->fetch();
$maxPage = ceil($cnt['cnt'] / 10); // ceilは小数点繰り上げ関数
$page = min($page, $maxPage); // 配列から一番小さな値を取り出す

$start = ($page - 1) * 10;

// membersの名前・postsの全カラムを取得
$posts = $dbh->prepare("SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC LIMIT ?,10");
$posts->bindParam(1, $start, PDO::PARAM_INT);
$posts->execute();


if (isset($_REQUEST['res'])) {
  //返信の処理
  $response = $dbh->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
  $response->execute(array($_REQUEST['res']));

  $table = $response->fetch();
  $message = '@' . $table['name'] . ' ' . $table['message'];
}

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
            <textarea name="message" cols="50" rows="5"><?php print(h($message)); ?></textarea>
            <input type="hidden" name="reply_post_id" value="<?php print(h($_REQUEST['res'])); ?>">
          </dd>
        </dl>
        <div>
          <p>
            <input type="submit" value="投稿する" />
          </p>
        </div>
      </form>

      <!-- membersの名前・postsの前カラムを取得したものを -->
      <?php foreach ($posts as $post) : ?>
        <div class="msg">
          <img src="/../member_picture/<?php print(h($post['picture'])); ?>" width="48" height="48" alt="<?php print(h($post['name'])); ?>" />
          <p><?php print(h($post['message'])); ?><span class="name">（<?php print(h($post['name'])); ?>）</span>[<a href="index.php?res=<?php print(h($post['id'])); ?>">Re</a>]</p>
          <p class="day"><a href="view.php?id=<?= h($post['id']); ?>"><?php print(h($post['created'])); ?></a>

            <?php if ($post['reply_message_id'] > 0) : ?>
              <a href="view.php?id=<?= h($post['reply_message_id']); ?>">
                返信元のメッセージ</a>
            <?php endif ?>
            <?php if ($_SESSION['id'] == $post['member_id']) : ?>
              [<a href="delete.php?id=<?= h($post['id']); ?>" style=" color: #F33;">削除</a>]
            <?php endif ?>

          </p>
        </div>
      <?php endforeach; ?>

      <ul class="paging">
        <?php if ($page > 1) : ?>
          <li><a href="index.php?page=<?= $page - 1; ?>">前のページへ</a></li>
        <?php else : ?>
          <li>前のページへ</li>
        <?php endif; ?>

        <?php if ($page < $maxPage) : ?>
          <li><a href="index.php?page=<?= $page + 1; ?>">次のページへ</a></li>
        <?php else : ?>
          <li>次のページへ</li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</body>

</html>