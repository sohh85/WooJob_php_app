## WooJob
 
海外での仕事探しを助けるWEBアプリケーションです！

URL　：　https://woojob.herokuapp.com/index.php
 
***
 ## 背景
日本には企業の口コミサイトがあり、実際に働いた方の情報を得られますが、海外企業向けのそのようなサイトはありません。その結果、英語に弱い日本人を悪用する企業も多く、1週間働いても8000円の報酬しか渡さない企業もあります。このような労働条件で働くことを余儀なくされる友人がおり、この課題を解決したいと思い制作しました。

***
## 概要や機能

### ログイン&会員登録ページ
- URLをクリックすると最初に表示されます
- 登録不要で「ゲスト」ボタンから使用することができます
![result](https://user-images.githubusercontent.com/67961122/107377788-ae304a00-6b2e-11eb-8d76-1d3e6aaa3a1a.gif)


### 投稿ページ
- 都市や時給などの必須項目に加え、おすすめ度、英語環境、追加説明など、で詳しく伝える事ができます
- 投稿後は、自動で管理者アカウントから「新着情報ページ」に通知されます
![result](https://user-images.githubusercontent.com/67961122/107379880-b5585780-6b30-11eb-9f19-5ba4e27cb6f4.gif)


### 検索ページ
- 条件検索機能により、「時給15ドル以上」✕「都市名」✕「英語環境」のように、より個人に合う仕事を検索できます
- 企業名検索では、一文字でも一致していたら表示されます
![result](https://user-images.githubusercontent.com/67961122/107380253-0ff1b380-6b31-11eb-8536-3a680cefec96.gif)


### 新着情報ページ
- 10投稿毎のページネーション
- 投稿日が表示されており、クリックすると詳細表示
- 削除/返信ができます

***

## テーブル

<img width="476" alt="woojobTable" src="https://user-images.githubusercontent.com/67961122/109409728-7a7f6c00-79d8-11eb-8056-61f4a3408615.png">


## 使用スキル
 
* PHP 7.4.13
* ext-mbstring (bundled with php)
* composer (2.0.8)
* apache (2.4.46)


***
現在、下記のリポジトリにて、Laravelを利用して機能を改良を行っています
Github URL　：　https://github.com/sohh85/Laravel-sns