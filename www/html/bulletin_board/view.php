<?php
session_start();
require_once '../pdo_connect.php';
require_once '../function.php';

if (empty($_REQUEST['id'])) {
  header('location: index.php');
  exit();
}

$posts = $dbh->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
$posts->execute(array($_REQUEST['id']));
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WooJob掲示板</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="/../css/sns_style.css">
  <!-- ファビコン -->
  <link rel="shortcut icon" href="../images/favicon.png" type="image/vnd.microsoft.icon">
  <link rel="icon" href="../images/favicon.png" type="image/vnd.microsoft.icon">
</head>

<body>
  <div id="wrap">
    <div class="content">
      <div id="head">
        <h1>WooJob掲示板</h1>
      </div>
      <div id="content">
        <p>&laquo;<a href="index.php">一覧にもどる</a></p>


        <?php if ($post = $posts->fetch()) : ?>

          <div class="msg">
            <img src="/../images/member_picture/<?php print(h($post['picture'])); ?>" width="250" height="250">
            <!-- js-autolinkでurlを有効か -->
            <pre class="js-autolink"><?= (h($post['message'])); ?><span class="name">（<?= h($post['name']); ?>）</span></pre>
            <small class="day"><?= h($post['created']); ?></small>
          </div>

        <?php else : ?>
          <p>その投稿は削除されたか、URLが間違えています</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <!-- URL有効化JSファイル -->
  <script src="../js/validate-url.js"></script>
</body>

</html>