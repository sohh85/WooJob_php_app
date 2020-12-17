<?php
require_once '../function.php';

// データ取得ロジック呼び出し
include_once('model.php');

// フォームで選ばれた$_GETの値を関数使用し検索
$jobData = getJobData($_GET);

?>
<!DOCTYPE HTML>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>条件検索</title>
    <!-- Bootstrap読み込み -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
</head>

<body>
    <h1 class="col-xs-6 col-xs-offset-3">条件検索フォーム</h1>
    <div class="col-xs-6 col-xs-offset-3 well">

        <!-- 条件検索フォーム  -->
        <form method="get">

            <div class="form-group">
                <label for="Name">企業・店の名前</label>
                <input name="name" class="form-control" id="Name" value="<?= isset($_GET['name']) ? h($_GET['name']) : '' ?>">
            </div>

            <div class="form-group">
                <label for="City">地域</label>
                <select name="city" class="form-control" id="City">
                    <option value="0" <?= empty($_GET['city']) ? 'selected' : '' ?>>選択しない</option>
                    <option value="1" <?= isset($_GET['city']) && $_GET['city'] == '1' ? 'selected' : '' ?>>シドニー</option>
                    <option value="2" <?= isset($_GET['city']) && $_GET['city'] == '2' ? 'selected' : '' ?>>メルボルン</option>
                    <option value="3" <?= isset($_GET['city']) && $_GET['city'] == '3' ? 'selected' : '' ?>>ケアンズ</option>
                    <option value="4" <?= isset($_GET['city']) && $_GET['city'] == '4' ? 'selected' : '' ?>>ゴールドコースト</option>
                    <option value="5" <?= isset($_GET['city']) && $_GET['city'] == '5' ? 'selected' : '' ?>>ブリズベン</option>
                    <option value="6" <?= isset($_GET['city']) && $_GET['city'] == '6' ? 'selected' : '' ?>>パース</option>
                </select>
            </div>

            <div class="form-group">
                <label for="Wage">時給</label>
                <select name="wage" class="form-control" id="Wage">
                    <option value="0" <?= empty($_GET['wage']) ? 'selected' : '' ?>>選択しない</option>
                    <option value="15" <?= isset($_GET['wage']) && $_GET['wage'] == '15' ? 'selected' : '' ?>>15ドル以上</option>
                    <option value="20" <?= isset($_GET['wage']) && $_GET['wage'] == '20' ? 'selected' : '' ?>>20ドル以上</option>
                    <option value="25" <?= isset($_GET['wage']) && $_GET['wage'] == '25' ? 'selected' : '' ?>>25ドル以上</option>
                </select>
            </div>

            <div class="form-group">
                <label for="Lang">英語使用頻度</label>
                <select name="lang" class="form-control" id="Lang">
                    <option value="0" <?= empty($_GET['lang']) ? 'selected' : '' ?>>選択しない</option>
                    <option value="1" <?= isset($_GET['lang']) && $_GET['lang'] == '1' ? 'selected' : '' ?>>ほぼない</option>
                    <option value="2" <?= isset($_GET['lang']) && $_GET['lang'] == '2' ? 'selected' : '' ?>>たまに</option>
                    <option value="3" <?= isset($_GET['lang']) && $_GET['lang'] == '3' ? 'selected' : '' ?>>頻繁に</option>
                </select>
            </div>

            <button type="submit" class="btn btn-default" name="search">検索</button>
        </form>

    </div>


    <div class="col-xs-6 col-xs-offset-3">
        <!-- ヒットしたデータを表示する  -->
        <?php if (isset($jobData) && count($jobData)) : ?>
            <p class="alert alert-success"><?= count($jobData) ?>件見つかりました。</p>
            <table class="table">
                <thead>
                    <tr>
                        <th>名前</th>
                        <th>性別</th>
                        <th>年齢</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobData as $row) : ?>
                        <tr>
                            <td><?= h($row['name']) ?></td>
                            <td><?= h($row['city']) ?></td>
                            <td><?= h($row['wage']) ?></td>
                            <td><?= h($row['lang']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p class="alert alert-danger">検索対象は見つかりませんでした。</p>
        <?php endif; ?>

    </div>
</body>

</html>