<?php
require_once '../function.php';

// 未ログイン or ログイン後1時間経過の場合再ログイン
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();
} else {
    header("Location: ../index.php");
    exit();
}

// 選択肢に使用する連想配列
$cities = array(1 => "シドニー", 2 => "メルボルン", 3 => "ケアンズ", 4 => "ゴールドコースト", 5 => "ブリズベン", 6 => "パース", 7 => "キャンベラ", 8 => "アデレード");
$languages = array(1 => "英語力必要無し", 2 => "必要な英語力（低）", 3 => "日常会話レベルの英語力", 4 => "必要な英語力（高）");

// 条件が指定された状態で「検索」が押されたら次の処理へ
if (!empty(array_filter($_GET))) {
    // データ取得ロジック呼び出し
    include_once('model.php');
    $jobData = getJobData($_GET);
}

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

    <!-- header -->
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
    <!-- /header -->

    <!-- 検索フォーム -->
    <div class="container">
        <div class="row py-5">
            <div class="col-lg-4">
                <div class="px-3">
                    <h1 class="h2">条件検索フォーム</h1>

                    <?php if (empty(array_filter($_GET)) && isset($_GET['search'])) : ?>
                        <p class="text-danger">検索条件を入力してください</p>
                    <?php else : ?>
                        <p class="my-4"><small>条件を指定し検索ボタンをクリックしてください</small></p>
                    <?php endif; ?>

                    <!-- 条件検索フォーム  -->
                    <form method="get">
                        <div class="form-group">
                            <label for="Name" class="mb-1 small">企業・店の名前</label>
                            <input name="name" class="form-control form-control-sm" id="Name" value="<?= isset($_GET['name']) ? h($_GET['name']) : '' ?>">
                        </div>

                        <div class="form-group">
                            <label for="City" class="mb-1 small">都市</label>
                            <select name="city" class="form-control form-control-sm" id="City">
                                <option value="" <?= empty($_GET['city']) ? 'selected' : '' ?>>選択しない</option>
                                <?php foreach ($cities as $key => $value) : ?>
                                    <option value="<?= $key; ?>" <?php if (isset($_GET['city']) && $_GET['city'] == "{$key}") echo 'selected' ?>><?= $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="Wage" class="mb-1 small">時給</label>
                            <select name="wage" class="form-control form-control-sm" id="Wage">
                                <option value="" <?= empty($_GET['wage']) ? 'selected' : '' ?>>選択しない</option>
                                <option value="15" <?= isset($_GET['wage']) && $_GET['wage'] == '15' ? 'selected' : '' ?>>15ドル以上</option>
                                <option value="20" <?= isset($_GET['wage']) && $_GET['wage'] == '20' ? 'selected' : '' ?>>20ドル以上</option>
                                <option value="25" <?= isset($_GET['wage']) && $_GET['wage'] == '25' ? 'selected' : '' ?>>25ドル以上</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="Language" class="mb-1 small">英語使用頻度</label>
                            <select name="language" class="form-control form-control-sm" id="Language">
                                <option value="" <?= empty($_GET['language']) ? 'selected' : '' ?>>選択しない</option>
                                <?php foreach ($languages as $key => $value) : ?>
                                    <option value="<?= $key; ?>" <?php if (isset($_GET['language']) && $_GET['language'] == "{$key}") echo 'selected' ?>><?= $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="button w-100" name="search">検索</button>

                    </form>
                </div>
            </div>
            <!-- /検索フォーム -->



            <div class="col-lg-8">

                <!-- ここに地域の画像と地域名載せる？ -->
                <div class="card text-white mb-3">
                    <img class="card-img" src="../images/barista.jpg" alt="都市の画像">
                    <div class="card-img-overlay">
                        <h4 class="card-title">ライトコース</h4>
                        <p class="card-text">ホームページ・ブログ開設など基礎を身に付けたい方向けコースです。</p>
                    </div>
                </div>

                <!-- ヒットしたデータを表示する  -->
                <?php if (isset($jobData) && count($jobData)) : ?>
                    <p class="alert alert-success"><?= count($jobData) ?>件見つかりました。</p>

                    <?php foreach ($jobData as $row) : ?>
                        <div class="card card-outline-secondary my-4">
                            <div class="card-header">
                                <?= h($row['name']) ?>
                            </div>
                            <div class="card-body">

                                <div>
                                    都市<?= $cities[(int)h($row['city_no'])] ?>
                                </div>
                                <div>
                                    時給<?= h($row['wage']) ?>$
                                </div>
                                <div>
                                    英語使用頻度<?= $languages[(int)h($row['language_no'])] ?>
                                </div>
                                <div>
                                    おすすめ度<?= str_repeat('⭐️', h($row['rating'])) ?>
                                </div>
                                <div>
                                    詳細情報<?= h($row['detail']) ?>
                                </div>
                                <div>
                                    <small class="text-muted">Posted on <?= h($row['created']) ?></small>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php else : ?>
                    <p class="alert alert-danger">検索対象が見つかりませんでした。</p>
                <?php endif; ?>
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
            <p class="m-0 text-center text-white">Copyright &copy; WooJob 2020</p>
        </div>
    </footer>

    <!-- Optional JavaScript -->
    <!-- jQuery first, Bootstrap JSの順番に読み込む -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

</body>

</html>