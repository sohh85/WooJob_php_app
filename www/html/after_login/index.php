<?php
session_start();
require_once '../pdo_connect.php';
require_once '../function.php';

// ログイン後1時間以上経過していたら再ログイン
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
  $_SESSION['time'] = time();

  $members = $dbh->prepare("SELECT * FROM members WHERE id=?");
  $members->execute(array($_SESSION['id']));
  $member = $members->fetch();
} else {
  header("location: ../index.php");
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
  $message = '@' . $table['name'] . ' ';
  // $message = '@' . $table['name'] . ' ' . $table['message'] . ' ';
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WooJob掲示板</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/82342a278b.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="shortcut icon" href="../images/favicon.png" type="image/vnd.microsoft.icon">
  <link rel="icon" href="../images/favicon.png" type="image/vnd.microsoft.icon">
</head>

<body>
  <div id="wrap">
    <!-- header読み込み -->
    <?php include("../header.php"); ?>
    <div class="content">
      <div id="head">
        <h1>WooJob掲示板</h1>
      </div>
      <div id="content">
        <div style="text-align: right"><a href="logout.php">ログアウト</a></div>

        <form action="" method="post">
          <dl>
            <dt><?= (h($member["name"])); ?>さん、メッセージをどうぞ</dt>
            <dd>
              <textarea name="message" cols="70" rows="5"><?= (h($message)); ?></textarea>
              <input type="hidden" name="reply_post_id" value="<?= (h($_REQUEST['res'])); ?>">
            </dd>
          </dl>
          <div class="mb-2">
            <input class="hover" type="submit" value="投稿する">
          </div>
        </form>

        <!-- membersの名前・postsの前カラムを取得したものを -->
        <?php foreach ($posts as $post) : ?>
          <div class="msg">

            <!-- プロフィール写真 -->
            <?php if (!empty($post['picture'])) : ?>
              <img src="/../member_picture/<?= (h($post['picture'])); ?>" width="48" height="48" alt="<?= (h($post['name'])); ?>">
            <?php else : ?>
              <div class="pic_box"></div>
            <?php endif; ?>

            <div class="float_text">
              <!-- メッセージと返信ボタン -->
              <p>
                <pre class="js-autolink"><?= (h($post['message'])); ?></pre>
                <div class="btn-radius-gradient-wrap">
                  <a class="btn btn-radius-gradient m-0" href="index.php?res=<?= (h($post['id'])); ?>">返信</a>
                </div>
              </p>

              <!-- プロフィール写真の下に表示する項目 -->
              <p class="under_pic mt-1"><span class="name"><i class="fas fa-user-circle"></i><?= (h($post['name'])); ?></span><a href="view.php?id=<?= h($post['id']); ?>"><?= (h($post['created'])); ?></a>

                <!-- 特定の投稿に対しての返信の場合表示 -->
                <?php if ($post['reply_message_id'] > 0) : ?>
                  <span class="reply"><a href="view.php?id=<?= h($post['reply_message_id']); ?>"><i class="fas fa-eye"></i>返信元メッセージ</a></span>
                <?php endif ?>

                <!-- ユーザ自身の投稿の場合、削除ボタン表示 -->
                <?php if ($_SESSION['id'] == $post['member_id']) : ?>
                  <a class="text-danger" href="delete.php?id=<?= h($post['id']); ?>">【削除】</a>
                <?php endif ?>
              </p>
            </div>
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
      <footer class="footer_bottom">
        <p>Copyright - 赤坂 壮, 2020 All Rights Reserved.</p>
      </footer>
    </div>
  </div>

  <script>
    $(function() {
      $('.js-autolink').each(function() {
        $(this).html($(this).html().replace(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig, "<a href='$1' class='m-0'>$1</a>"));
      });
    });

    $(function() {
      $('.js-menu__item__link').each(function() {
        $(this).on('click', function() {
          $(this).toggleClass('on');
          $("+.submenu", this).slideToggle()
          return false;
        });
      });
    });
  </script>
</body>

</html>