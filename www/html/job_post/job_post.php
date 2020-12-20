<?php


$cities = array(1 => "シドニー", 2 => "メルボルン", 3 => "ケアンズ", 4 => "ゴールドコースト", 5 => "ブリズベン", 6 => "パース", 7 => "キャンベラ", 8 => "アデレード");


?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>仕事情報投稿</title>

    <!-- Bootstrap core CSS -->
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
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container">
        <div class="row">

            <div class="col-lg-8">

                <!-- <div class="card mt-4">
                    <img class="card-img-top img-fluid" src="http://placehold.it/900x400" alt="">
                    <div class="card-body">
                        <h3 class="card-title">Product Name</h3>
                        <h4>$24.99</h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sapiente dicta fugit fugiat hic aliquam itaque facere, soluta. Totam id dolores, sint aperiam sequi pariatur praesentium animi perspiciatis molestias iure, ducimus!</p>
                        <span class="text-warning">&#9733; &#9733; &#9733; &#9733; &#9734;</span>
                        4.0 stars
                    </div>
                </div> -->
                <!-- /.card -->

                <div class="card card-outline-secondary my-4">
                    <div class="card-header h4 py-3">
                        仕事情報投稿フォーム
                    </div>
                    <div class="card-body">

                        <form method="get">

                            <div class="form-group">
                                <label for="Name">企業・店の名前</label>
                                <input name="name" type="text" class="form-control form-control-sm" id="Name" value="<?= isset($_GET['name']) ? h($_GET['name']) : '' ?>" autofocus required>
                            </div>

                            <div class="form-group">
                                <label for="City">地域</label>
                                <select name="city" class="form-control form-control-sm" id="City" required>
                                    <option value="" disabled selected>選択してください</option>
                                    <?php foreach ($cities as $key => $value) : ?>
                                        <option value="<?= $key; ?>" <?php if (isset($_GET['city']) && $_GET['city'] == "{$key}") echo 'selected' ?>><?= $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="Wage">時給（$）</label>
                                <!-- 1以下の数字と0.1より細かい値は記入できない -->
                                <input type="number" step="0.1" min="1" name="wage" class="form-control form-control-sm" id="Wage" value="<?= isset($_GET['wage']) ? h($_GET['wage']) : '' ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="Language">英語使用頻度</label>
                                <select name="language" class="form-control form-control-sm" id="Language" required>

                                    <option value="" disabled selected>選択してください</option>

                                    <option value="1" <?= isset($_GET['language']) && $_GET['language'] == '1' ? 'selected' : '' ?>>ほぼない</option>
                                    <option value="2" <?= isset($_GET['language']) && $_GET['language'] == '2' ? 'selected' : '' ?>>たまに</option>
                                    <option value="3" <?= isset($_GET['language']) && $_GET['language'] == '3' ? 'selected' : '' ?>>頻繁に</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="Rating">おすすめ度</label>
                                <select name="rating" class="form-control form-control-sm" id="Rating" required>
                                    <option value="" disabled selected>選択してください</option>
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <option value="<?= $i ?>"><?= str_repeat('⭐️', $i) ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="Detail">企業・店の名前</label>
                                <textarea name="detail" class="form-control form-control-sm" placeholder="好きなテキストを入力" id="Detail" value="<?= isset($_GET['detail']) ? h($_GET['detail']) : '' ?>" required>></textarea>
                                <!-- <input name="detail" type="text" class="form-control form-control-sm" id="Detail" value="<?= isset($_GET['detail']) ? h($_GET['detail']) : '' ?>" required> -->
                            </div>

                            <hr>
                            <button type="submit" class="btn btn-info mt-4 px-5" name="post">情報を投稿する</button>
                        </form>
                    </div>
                </div>
                <!-- /.card -->

            </div>
            <!-- /.col-lg-9 -->


            <!-- <div class="col-lg-3">
                <h1 class="my-4">Shop Name</h1>
                <div class="list-group">
                    <a href="#" class="list-group-item active">Category 1</a>
                    <a href="#" class="list-group-item">Category 2</a>
                    <a href="#" class="list-group-item">Category 3</a>
                </div>
            </div> -->
            <!-- /.col-lg-3 -->

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

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>