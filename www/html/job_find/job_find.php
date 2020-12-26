<?php
session_start();
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

// データ取得model呼び出し
include_once('model.php');
$jobData = getJobData($_GET);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>条件検索</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <!-- ファビコン -->
    <link rel="shortcut icon" href="../images/favicon.png" type="image/vnd.microsoft.icon">
    <link rel="icon" href="../images/favicon.png" type="image/vnd.microsoft.icon">
    <!-- font awesome -->
    <script src="https://kit.fontawesome.com/82342a278b.js" crossorigin="anonymous"></script>
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
                        <a class="nav-link active" href="#">検索フォーム<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../bulletin_board/index.php">掲示板</a>
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
                <!-- <div class="px-4 pb-4"> -->
                <div class="card">
                    <div class="h4 text-center card-header">条件検索 <i class="fas fa-search-plus"></i></div>





                    <!-- 条件検索フォーム  -->
                    <div class="card-body">
                        <?php if (empty(array_filter($_GET)) && isset($_GET['search'])) : ?>
                            <p class="my-2 text-danger text-center"><small>検索条件を入力してください</small></p>
                        <?php else : ?>
                            <p class="mb-3 text-center"><small>条件を1つ以上指定してください</small></p>
                        <?php endif; ?>
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
            </div>
            <!-- /検索フォーム -->


            <!-- ヒットしたデータを表示する  -->
            <div class="col-lg-8 px-5">
                <?php if (isset($jobData) && count($jobData)) : ?>
                    <p class="alert alert-success"><?= count($jobData) ?>件表示しています。</p>

                    <?php foreach ($jobData as $row) : ?>
                        <div class="card card-outline-secondary my-4">
                            <div class="card-header lead">
                                <?= h($row['name']) ?>
                            </div>
                            <div class="card-body">

                                <span class="btn btn-light btn-sm">
                                    <?= $cities[(int)h($row['city_no'])] ?>
                                </span>

                                <span class="btn btn-light btn-sm">
                                    時給<?= h($row['wage']) ?>$
                                </span>

                                <span class="btn btn-light btn-sm">
                                    <?= $languages[(int)h($row['language_no'])] ?>
                                </span>

                                <span class="btn btn-light btn-sm">
                                    おすすめ度<?= str_repeat('⭐️', h($row['rating'])) ?>
                                </span>

                                <?php if (!empty(h($row['detail']))) : ?>
                                    <p class="mt-4"><span class="font-small"><i class="fas fa-caret-square-down"></i> 詳細情報</span><br>
                                        <pre class="js-autolink"><?= h($row['detail']) ?></pre>
                                    </p>
                                <?php endif; ?>

                                <hr>
                                <div><small class="text-muted">Posted on <?= h($row['created']) ?></small>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php elseif (isset($_GET['search']) && empty($jobData)) : ?>
                    <p class="alert alert-danger">検索対象が見つかりませんでした。</p>
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <!-- URL有効化JSファイル -->
    <script src="../js/validate-url.js"></script>
</body>

</html>