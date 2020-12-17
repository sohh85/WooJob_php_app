<?php
require_once '../function.php';

// データ取得ロジック呼び出し
include_once('model.php');

$userData = getUserData($_GET);

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
    <h1 class="col-xs-6 col-xs-offset-3">検索フォーム</h1>
    <div class="col-xs-6 col-xs-offset-3 well">

        <!-- 条件検索フォーム  -->
        <form method="get">
            <div class="form-group">
                <label for="InputName">企業・店の名前</label>
                <input name="name" class="form-control" id="InputName" value="<?= isset($_GET['name']) ? h($_GET['name']) : '' ?>">
            </div>
            <div class="form-group">
                <label for="InputSex">地域</label>
                <select name="sex" class="form-control" id="InputSex">
                    <option value="0" <?= empty($_GET['sex']) ? 'selected' : '' ?>>選択しない</option>
                    <option value="1" <?= isset($_GET['sex']) && $_GET['sex'] == '1' ? 'selected' : '' ?>>シドニー</option>
                    <option value="2" <?= isset($_GET['sex']) && $_GET['sex'] == '2' ? 'selected' : '' ?>>メルボルン</option>
                </select>
            </div>
            <div class="form-group">
                <label for="Wage">時給</label>
                <select name="wage" class="form-control" id="Wage">
                    <option value="0" <?= empty($_GET['wage']) ? 'selected' : '' ?>>選択しない</option>
                    <option value="10" <?= isset($_GET['wage']) && $_GET['wage'] == '10' ? 'selected' : '' ?>>15ドル以上</option>
                    <option value="20" <?= isset($_GET['wage']) && $_GET['wage'] == '20' ? 'selected' : '' ?>>20ドル以上</option>
                    <option value="30" <?= isset($_GET['wage']) && $_GET['wage'] == '30' ? 'selected' : '' ?>>25ドル以上</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">英語環境</label>
                <select name="age" class="form-control" id="InputAge">
                    <option value="0" <?= empty($_GET['age']) ? 'selected' : '' ?>>選択しない</option>
                    <option value="10" <?= isset($_GET['age']) && $_GET['age'] == '10' ? 'selected' : '' ?>>10代</option>
                    <option value="20" <?= isset($_GET['age']) && $_GET['age'] == '20' ? 'selected' : '' ?>>20代</option>
                    <option value="30" <?= isset($_GET['age']) && $_GET['age'] == '30' ? 'selected' : '' ?>>30代</option>
                </select>
            </div>

            <button type="submit" class="btn btn-default" name="search">検索</button>
        </form>

    </div>


    <div class="col-xs-6 col-xs-offset-3">
        <!-- ヒットしたデータを表示する  -->
        <?php if (isset($userData) && count($userData)) : ?>
            <p class="alert alert-success"><?= count($userData) ?>件見つかりました。</p>
            <table class="table">
                <thead>
                    <tr>
                        <th>名前</th>
                        <th>性別</th>
                        <th>年齢</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userData as $row) : ?>
                        <tr>
                            <td><?= h($row['name']) ?></td>
                            <td><?= h($row['sex'] == 1 ? '男性' : '女性') ?></td>
                            <td><?= h($row['age']) ?></td>
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