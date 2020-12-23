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
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  <link rel="stylesheet" href="/../css/sns_style.css">
  <!-- ファビコン -->
  <link rel="shortcut icon" href="../images/favicon.png" type="image/vnd.microsoft.icon">
  <link rel="icon" href="../images/favicon.png" type="image/vnd.microsoft.icon">
</head>

<body>

  <!-- header -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#"></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="../job_post/job_post.php">投稿フォーム</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../job_find/job_find.php">検索フォーム</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="#">掲示板<span class="sr-only">(current)</span></a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- /header -->

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

  <!-- Footer -->
  <footer class="py-5 bg-white mt-5">
    <div class="container">
      <p class="m-0 text-center text-secondary">Copyright &copy; WooJob 2021</p>
    </div>
  </footer>
  <!-- Optional JavaScript -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <!-- URL有効化JSファイル -->
  <script src="../js/validate-url.js"></script>
</body>

</html>