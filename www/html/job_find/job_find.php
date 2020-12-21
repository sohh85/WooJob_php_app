<?php
require_once '../function.php';
require_once '../pdo_connect.php';

// データ取得ロジック呼び出し
// include_once('model.php');

// フォームで選ばれた$_GETの値を関数使用し検索
// $jobData = getJobData($_GET);

$cities = array(1 => "シドニー", 11 => "メルボルン", 21 => "ケアンズ", 31 => "ゴールドコースト", 41 => "ブリズベン", 51 => "パース", 61 => "キャンベラ", 71 => "アデレード");
$language = array("全く必要ない", "たまに英語を使用", "よく英語を使用", "頻繁に英語を使用");
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>条件検索</title>
    <!-- Bootstrap読み込み -->

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
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
                    <li class="nav-item">
                        <a class="nav-link" href="#">投稿フォーム</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">検索フォーム<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">掲示板</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container widthSize">
        <div class="row">

            <div class="col-lg-12 my-5">
                <h1 class="my-2">Shop Name</h1>

                <div class="card">
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


            <div class="col-lg-4 my-5">
                <div class="px-3">

                    <h1 class="h2">条件検索フォーム</h1>
                    <p class="my-4"><small>条件を指定し検索ボタンをクリックしてください</small></p>

                    <!-- 条件検索フォーム  -->
                    <form method="get">

                        <div class="form-group">
                            <label for="Name" class="mb-1 small">企業・店の名前</label>
                            <input name="name" class="form-control form-control-sm" id="Name" value="<?= isset($_GET['name']) ? h($_GET['name']) : '' ?>">
                        </div>

                        <div class="form-group">
                            <label for="City" class="mb-1 small">地域</label>
                            <select name="city" class="form-control form-control-sm" id="City">
                                <option value="0" <?= empty($_GET['city']) ? 'selected' : '' ?>>選択しない</option>
                                <?php foreach ($cities as $key => $value) : ?>
                                    <option value="<?= $key; ?>" <?php if (isset($_GET['city']) && $_GET['city'] == "{$key}") echo 'selected' ?>><?= $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="Wage" class="mb-1 small">時給</label>
                            <select name="wage" class="form-control form-control-sm" id="Wage">
                                <option value="0" <?= empty($_GET['wage']) ? 'selected' : '' ?>>選択しない</option>
                                <option value="15" <?= isset($_GET['wage']) && $_GET['wage'] == '15' ? 'selected' : '' ?>>15ドル以上</option>
                                <option value="20" <?= isset($_GET['wage']) && $_GET['wage'] == '20' ? 'selected' : '' ?>>20ドル以上</option>
                                <option value="25" <?= isset($_GET['wage']) && $_GET['wage'] == '25' ? 'selected' : '' ?>>25ドル以上</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="Language" class="mb-1 small">英語使用頻度</label>
                            <select name="language" class="form-control form-control-sm" id="Language">
                                <option value="0" <?= empty($_GET['language']) ? 'selected' : '' ?>>選択しない</option>
                                <?php foreach ($language as $value) : ?>
                                    <option value="<?= $value; ?>" <?php if (isset($_GET['language']) && $_GET['language'] == "{$value}") echo 'selected' ?>><?= $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-outline-info btn-block mt-4" name="search">検索</button>
                    </form>

                </div>
            </div>
            <!-- /.col-lg-4 -->



            <div class="col-lg-8 my-5">
                <!-- /.card -->
                <div class="card card-outline-secondary my-4">
                    <div class="card-header">
                        検索結果
                    </div>
                    <div class="card-body">

                        <!-- ヒットしたデータを表示する  -->
                        <?php if (isset($jobData) && count($jobData)) : ?>
                            <p class="alert alert-success"><?= count($jobData) ?>件見つかりました。</p>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>企業名</th>
                                        <th>都市</th>
                                        <th>時給</th>
                                        <th>英語使用頻度</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($jobData as $row) : ?>
                                        <tr>
                                            <td><?= h($row['name']) ?></td>
                                            <td><?= h($row['city']) ?></td>
                                            <td><?= h($row['wage']) ?></td>
                                            <td><?= h($row['language']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p class="alert alert-danger">検索対象は見つかりませんでした。</p>
                        <?php endif; ?>

                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
                        <small class="text-muted">Posted by Anonymous on 3/1/17</small>
                        <hr>

                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
                        <small class="text-muted">Posted by Anonymous on 3/1/17</small>
                        <hr>

                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col-lg-8 -->
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