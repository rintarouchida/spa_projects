# アプリ概要

### タイトル名：もくもくMAP
アプリURL
**[https://service.mokumoku-map.com/](https://service.mokumoku-map.com/)**

以下のアドレス、パスワードを用いてテストアカウントとしてログインできます。<br>
(メールアドレス)test@example.com<br>
(パスワード)password


## サービス概要
もくもくMAPは、エンジニア同士が交流するためのプラットフォームです。勉強会を主催し、参加したいエンジニア同士が効率よく繋がり、切磋琢磨し成長していくことを目的としています。
<img width="1629" alt="" src="">


## **このアプリを作成した理由**
私は、エンジニアとして成長していくために、自分だけで学ぶのではなく他のエンジニアと一緒に学び、互いの知識を共有することが非常に重要だと感じています。
しかし、現実的には、同じ興味を持つエンジニアを見つけることは難しいと感じています。
そのような課題を解決するために、“もくもくMAP”を開発しました。
このアプリは、エンジニアが勉強会を簡単に開催でき、同じ興味を持つ仲間を見つけることができます。
このアプリを通じて多くのエンジニアが切磋琢磨して成長していける環境を作っていってほしいと考えています。


## 開発期間

開発期間：2023年9月~2024年2月

開発時間：概算ですが大体300時間くらい開発に費やしました。

平日1時間半。休日3~5時間
週：約15時間

月：約60時間

合計：約300時間

## 使用画面のイメージ

### TOP画面
<img width="1318" alt="redme_top_page" src="https://github.com/rintarouchida/spa_projects/assets/102397462/372a0d5a-33a9-428c-a36f-683f6743ca85">



### もくもく会検索画面
<img width="1346" alt="readme_search" src="https://github.com/rintarouchida/spa_projects/assets/102397462/c7cd3a26-b0f1-4941-99de-e26a156f1ef6">



### メッセージ画面
<img width="1330" alt="readme_message" src="https://github.com/rintarouchida/spa_projects/assets/102397462/c5aacf6d-daf0-428c-adb7-aab60e6b33bb">



# 機能一覧

### ログインユーザー関連の機能

- ユーザー登録
- ユーザーログイン
- ログインユーザー情報表示
- ログインユーザー情報更新

### パスワード関連

- パスワードリセット用メール送信機能
- パスワードリセット機能

### もくもく会関連機能

- もくもく会一覧表示機能
- 作成したもくもく会一覧表示機能
- 参加したもくもく会一覧表示機能
- もくもく会詳細表示機能
- もくもく会参加機能
- もくもく会参加キャンセル機能
- もくもく会検索機能

### メッセージ機能

- 自分が参加したもくもく会のメッセージ一覧表示機能
- 自分が主催したもくもく会のメッセージ一覧表示機能
- メッセージ送信機能


# 技術・環境

### フロントエンド

- HTML / CSS / Vue.js / Nuxt.js

### バックエンド

- PHP
- Laravel
- PHPUnit

### データベース

- MySQL

### インフラ

- AWS（VPC、EC2、RDS、ACM、ALB、S3）

### その他ツール

- VSCODE
- Git / Github
- Docker


# 技術選定理由

技術を選定する上で、主に以下2点の観点を重視して選択しました。

- 独学でも学びやすく実装できるまでたどり着けるか（途中で挫折しないか）
- 現状、主流のWebアプリ開発で使用されているか

>### バックエンド：PHP/Laravel

- 役割：APIとしての機能。認証、クエリ発行など

採用理由
- 実際の業務でそのフレームワークを使用していたため
- また、他の主要なサーバーサイドのフレームワークと比較して、より直感的なDBのリレーションの定義ができる点で優れていると思ったため

>### テストコード：PHPUnit

- 役割 : バックエンドのテストコード

採用理由
- PHPUnitはPHPで最も広く,長年使われているテストフレームワークであるため

>### フロントエンド：Vue.js/Nuxt.js

役割：実際にブラウザで表示される部分、SPAで運用

採用理由

- ファイルシステムベースでのルーティングが自動生成ができるため効率良く開発できるため
- プラグインとミドルウェアのシステムを提供しており、効率良く開発できるため


>### 開発環境：Docker/Docker-compose

役割：仮想開発環境の構築

採用理由

- Dockerを使用することで、開発環境をコードとして定義し、開発環境の一貫性を保てるため
- また、docker-composeを使用することで、複数のコンテナを簡単に管理し、起動・停止することができるので効率よく開発ができるため

>### インフラ：AWS

役割：本番環境の運用

採用理由

- クラウドサービスの中でシェアが世界でNo.1であるため
- 、それに関する技術記事も非常に豊富なので、より効率良く確実にデプロイに必要な情報を得られると思ったため


# 工夫した点

### テストコード
- API側での不備をなくすためにテストコードをしっかり書くようにした。実際に私が今参画している現場では、カバレッジが80%上回るように書くというルールがあるので、本アプリケーションでも、カバレッジが80%を上回るように実装した
  <img width="1336" alt="coverage" src="https://github.com/rintarouchida/spa_projects/assets/102397462/e91168c2-fbe2-46b7-8e4b-7b5a9131ad5d">

### Eagerローディング
- API側でリレーションが設定されたデータを取得する際にN+1問題が生じると、データベースへのクエリ数が増え、アプリケーションのレスポンスに時間がかかりパフォーマンスが大きく低下する可能性があるので、データを取得する際には必ずwithメソッドを用いて必要なリレーションデータも一緒にロードするようにした。

### FatController対策
- コードの整理と保守性の向上を目指し、LaravelのコントローラーがFatControllerにならないようにすることに重点を置いた。具体的には、CRUD系の処理はServiceクラスに、レスポンスデータの形成はResourceクラスに分けるようにした。これにより、単一のクラスが複数の責任を持つことを避け、各クラスの役割を明確にした。

### API設計書
開発者がAPIの詳細を理解しやすくなるように、Swaggerを導入しAPIの設計書を作成した
https://rintarouchida.github.io/spa_projects/swagger/

### レスポンシブ対応
- スマホでアプリケーションを開いた際のUIを考慮し、フロント側でレスポンシブ対応を行なった。
