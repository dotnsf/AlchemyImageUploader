# ImgUploader

PHP + MySQL + AlchemyAPI による簡易画像アップローダーサンプル

## 準備

- PHP + MySQL + httpd 環境の構築

- AlchemyAPI で API Key の取得

## ファイル

- createtables.php : 必要なテーブルを作成する（最初に一回実行）

- credentials.php : 接続情報（このファイルをカスタマイズする必要有り）

- delete.php : 指定した画像の情報を DB から削除する

- image.php : 個別の画像とそのメタ情報を出力するページ

- index.php : 画像一覧を出力するページ（メインページ）

- loadimg.php : 画像バイナリを出力する

- up.php : アップロードされた画像バイナリを受け取って DB に格納する

- uptest.html : アップロードテスト用

## 開発者

- K.Kimura ( dotnsf@gmail.com ), all rights reserved.


