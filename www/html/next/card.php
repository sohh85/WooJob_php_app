<!doctype html>
<html lang="ja">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSSの読み込み -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <title>Bootstrap Practice</title>
</head>

<body>
    <!-- ナビゲーションメニュー -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand text-dark" href="#">Portfolio</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ">
                <a class="nav-link text-dark" href="#">Home <span class="sr-only">(current)</span></a>
                <a class="nav-link text-dark" href="#skill">Skill</a>
                <a class="nav-link text-dark" href="#works">Works</a>
                <a class="nav-link text-dark" href="#contact">Contact</a>
            </div>
        </div>
    </nav>


    <!-- カード -->
    <div class="container py-4" id="works">
        <h2>Works</h2>
        <p>This is sample description.This is sample description.This is sample description.This is sample description.This is sample description.This is sample description.</p>
        <div class="card-deck">
            <div class="card">
                <img src="img/sample4.jpg" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Work1</h5>
                    <p class="card-text">This is sample description.This is sample description.This is sample description.This is sample description.</p>
                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                </div>
            </div>
            <div class="card">
                <img src="img/sample4.jpg" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Work2</h5>
                    <p class="card-text">This is sample description.This is sample description.This is sample description.This is sample description.</p>
                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                </div>
            </div>
            <div class="card">
                <img src="img/sample4.jpg" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Work3</h5>
                    <p class="card-text">This is sample description.This is sample description.This is sample description.This is sample description.</p>
                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, Popper.js, Bootstrap JSの順番に読み込む -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>