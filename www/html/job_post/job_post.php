<?php
session_start();
require_once '../pdo_connect.php';
require_once '../function.php';

// ログイン済でない場合
// if (!isset($_SESSION['join'])) {
//     header('Location: ..index.php');
//     exit();
// }


// 選択肢に使用する連想配列
$cities = array(1 => "シドニー", 2 => "メルボルン", 3 => "ケアンズ", 4 => "ゴールドコースト", 5 => "ブリズベン", 6 => "パース", 7 => "キャンベラ", 8 => "アデレード");
$language = array(1 => "全く必要ない", 2 => "たまに英語を使用", 3 => "よく英語を使用", 4 => "頻繁に英語を使用");



// 登録ボタン押されたら次の処理へ
if (isset($_POST['post'])) {

    $stmt = $dbh->prepare('INSERT INTO job_data SET name=?, city_id=?, wage=?, language=?, rating=?, detail=?, created=NOW()+INTERVAL 9 HOUR');

    $stmt->bindValue(1, $_POST['name'], PDO::PARAM_STR);
    $stmt->bindValue(2, $_POST['city'], PDO::PARAM_INT);
    $stmt->bindValue(3, $_POST['wage'], PDO::PARAM_INT);
    $stmt->bindValue(4, $_POST['language'], PDO::PARAM_STR);
    $stmt->bindValue(5, $_POST['rating'], PDO::PARAM_INT);
    $stmt->bindValue(6, $_POST['detail'], PDO::PARAM_STR);

    $stmt->execute();
}


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>仕事情報投稿</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">WooJob</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">投稿フォーム<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">検索フォーム</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">掲示板</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container">
        <div class="row">

            <div class="col-lg-7">
                <div class="card card-outline-secondary my-4">
                    <div class="card-header h4 py-3">
                        仕事情報投稿フォーム
                    </div>
                    <div class="card-body">
                        <form method="post">

                            <div class="form-group">
                                <label for="Name">企業・店の名前<span class="text-danger"> *</span></label>
                                <input name="name" type="text" class="form-control form-control-sm" id="Name" value="<?= isset($_POST['name']) ? h($_POST['name']) : '' ?>" autofocus required>
                            </div>

                            <div class="form-group">
                                <label for="City">地域<span class="text-danger"> *</span></label>
                                <select name="city" class="form-control form-control-sm" id="City" required>
                                    <option value="" disabled selected>選択してください</option>
                                    <?php foreach ($cities as $key => $value) : ?>
                                        <option value="<?= $key; ?>" <?php if (isset($_POST['city']) && $_POST['city'] == "{$key}") echo 'selected' ?>><?= $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="Wage">時給 ($)<span class="text-danger">*</span></label>
                                <!-- 1以下の数字と0.1より細かい値は記入できない -->
                                <input type="number" step="0.1" min="1" name="wage" class="form-control form-control-sm" id="Wage" value="<?= isset($_POST['wage']) ? h($_POST['wage']) : '' ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="Language">英語使用頻度<span class="text-danger"> *</span></label>
                                <select name="language" class="form-control form-control-sm" id="Language" required>
                                    <option value="" disabled selected>選択してください</option>
                                    <?php foreach ($language as $key => $value) : ?>
                                        <option value="<?= $key; ?>" <?php if (isset($_POST['language']) && $_POST['language'] == "{$key}") echo 'selected' ?>><?= $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="Rating">おすすめ度<span class="text-danger"> *</span></label>
                                <select name="rating" class="form-control form-control-sm" id="Rating" required>
                                    <option value="" disabled selected>選択してください</option>
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <option value="<?= $i ?>" <?php if (isset($_POST['rating']) && $_POST['rating'] == "{$i}") echo 'selected' ?>><?= str_repeat('⭐️', $i) ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="Detail">追記情報</label>
                                <textarea rows="6" cols="60" name="detail" class="form-control form-control-sm" placeholder="「場所や給与に関しての詳細」「実際に働いてみて感じたこと」などを自由にご記入下さい" id="Detail"><?= isset($_POST['detail']) ? h($_POST['detail']) : '' ?></textarea>
                            </div>

                            <hr>
                            <button type="submit" class="button" name="post">情報を投稿する</button>
                        </form>
                    </div>
                </div>
                <!-- /.card -->

            </div>
            <!-- /.col-lg-7 -->


            <!-- 何を表示するか未定 col-lg-5 -->
            <div class="col-lg-5">

                <h1 class="my-4">Shop Name</h1>
                <div class="list-group">
                    <a href="#" class="list-group-item active">Category 1</a>
                    <a href="#" class="list-group-item">Category 2</a>
                    <a href="#" class="list-group-item">Category 3</a>
                </div>


                <div class="card mt-4">
                    <img class="card-img-top img-fluid" src="http://placehold.it/900x400" alt="">
                    <div class="card-body">
                        <h3 class="card-title">Product Name</h3>
                        <h4>$24.99</h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sapiente dicta fugit fugiat hic aliquam itaque facere, soluta. Totam id dolores, sint aperiam sequi!</p>
                        <span class="text-warning">&#9733; &#9733; &#9733; &#9733; &#9734;</span>
                        4.0 stars
                    </div>
                </div>

            </div>
            <!-- /.col-lg-5 -->

        </div>
    </div>
    <!-- /.container -->

    <!-- Footer -->
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; Your Website 2020</p>
        </div>
        <!-- /.container -->
    </footer>

    <!-- Optional JavaScript -->
    <!-- jQuery first, Bootstrap JSの順番に読み込む -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

</body>

</html>