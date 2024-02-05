# アプリ概要

### タイトル名：もくもくMAP
Githubリポジトリ
**[https://github.com/rintarouchida/spa_projects](https://github.com/rintarouchida/spa_projects)**


## サービス概要
もくもくMAPは、エンジニア同士が効率よく交流するためのプラットフォームです。勉強会を主催したり、参加したいエンジニア同士が繋がることを目的としています。
<img width="1629" alt="" src="">


## **このアプリを作成した理由**

自分自身は、エンジニアとして成長していくためには、自分だけでなく他のエンジニアと一緒に学び、互いの知識を共有することが非常に重要だと感じています。しかし、現実的には、同じ興味を持つエンジニアを見つけるのはなかなか難しいですし、また勉強会を開催する場所を探すのも苦労すると思います。そのような課題を解決するために、"もくもくMAP"を開発しました。これにより、エンジニアは自分たちの勉強会を簡単に開催でき、また同じ興味を持つ仲間を見つけることができます。私自身もエンジニアとして成長するためには他者との切磋琢磨が不可欠だと感じているため、このアプリを通じて多くのエンジニアが切磋琢磨して成長していける環境を作っていってほしいと考えています。

## 開発期間

開発期間：2023年9月~2024年2月

開発時間：概算ですが大体300時間くらい開発に費やしました。

平日1時間半。休日3~5時間
週：約15時間

月：約60時間

合計：約300時間

## 使用画面のイメージ

### TOP画面
<img width="1629" alt="" src="">

### もくもく会検索画面
<img width="1595" alt="" src="">

### メッセージ画面
<img width="1569" alt="" src="">



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

### バッグエンド

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
- 実際の業務でそのフレームワークを使用していたのが一番大きな理由です。また、他の主要なサーバーサイドのフレームワークと比較して、より直感的なDBのリレーションの定義や、多様なCLIツールがあるので、効率的にAPI開発ができると思い採用しました。

>### テストコード：PHPUnit

- 役割 : バックエンドのテストコード

採用理由
- PHPUnitはPHPで最も広く,長年使われているテストフレームワークであり、安定性と信頼性が高いと思ったので採用しました。

>### フロントエンド：Vue.js/Nuxt.js

役割：実際にブラウザで表示される部分、SPAで運用

採用理由

- ファイルシステムベースでのルーティングが自動生成や、プラグインとミドルウェアのシステムを提供しており、効率良く開発できると思い採用しました。


>### 開発環境：Docker/Docker-compose

役割：仮想開発環境の構築

採用理由

- Dockerを使用することで、開発環境をコードとして定義し、開発環境の一貫性を保てる点が非常に優れていると思い使用しました。また、docker-composeを使用することで、複数のコンテナを簡単に管理し、起動・停止することができるので効率よく開発ができます。

>### インフラ：AWS

役割：本番環境の運用

採用理由

- クラウドサービスの中でシェアが世界でNo.1であり、それに関する技術記事も非常に豊富なので、より効率良く確実にデプロイができると思いました。また、機能の豊富さと拡張性にも優れていて、必要なリソースと機能を簡単に追加することが可能だと思い採用しました。


# 工夫した点

### テストコード
- API側での不備をなくすためにテストコードをしっかり書くようにしました。実際に私が今参画している現場では、カバレッジが85%上回るように書くというルールがあるので、今回のポートフォリオでも、カバレッジが85%を上回るように実装しました。

### Eagerローディング
- API側でリレーションが設定されたデータを取得する際にN+1問題が生じると、データベースへのクエリ数が増え、アプリケーションのレスポンスに時間がかかりパフォーマンスが大きく低下する可能性があるので、データを取得する際には必ずwithメソッドを用いて必要なリレーションデータも一緒にロードするようにしました。

### FatController対策
- コードの整理と保守性の向上を目指し、LaravelのコントローラーがFatControllerにならないようにすることに重点を置きました。具体的には、データベースからのクエリー取得はServiceクラスに、データの抽出はResourceクラスに分けるようにしました。これにより、単一のクラスが複数の責任を持つことを避け、各クラスの役割を明確にしました。
